<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\DokumentasiFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    // =============================================
    // GET /api/dokumentasi
    // Semua user (admin & guru) bisa akses
    // =============================================
    public function index(Request $request)
    {
        $query = Dokumentasi::with(['foto', 'guru'])
            ->where('status', 'aktif');

        if ($request->query('kelas_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id)
                    ->orWhereNull('kelas_id');
            });
        }

        if ($request->query('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $dokumentasi = $query->orderByDesc('tanggal')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $dokumentasi->map(fn($d) => $this->format($d)),
        ]);
    }

    // =============================================
    // GET /api/dokumentasi/{id}
    // Semua user bisa akses
    // =============================================
    public function show($id)
    {
        $dokumentasi = Dokumentasi::with(['foto', 'guru'])->find($id);

        if (!$dokumentasi) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $this->format($dokumentasi),
        ]);
    }

    // =============================================
    // POST /api/dokumentasi
    // Hanya guru
    // =============================================
    public function store(Request $request)
    {
        $this->checkGuru($request);

        $request->validate([
            'judul'      => ['required', 'string', 'max:150'],
            'deskripsi'  => ['nullable', 'string'],
            'kelas_id'   => ['nullable', 'string'],
            'tanggal'    => ['required', 'date'],
            'foto.*'     => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            $dokumentasi = Dokumentasi::create([
                'user_id'   => $request->user()->id,
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'kelas_id'  => $request->kelas_id,
                'tanggal'   => $request->tanggal,
                'status'    => 'aktif',
            ]);

            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $path = $file->store('dokumentasi', 'public');
                    DokumentasiFoto::create([
                        'id_dokumentasi' => $dokumentasi->id_dokumentasi,
                        'upload_file'    => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Dokumentasi berhasil ditambahkan.',
                'data'    => $this->format($dokumentasi->load('foto')),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // =============================================
    // PUT /api/dokumentasi/{id}
    // Hanya guru pemilik data
    // =============================================
    public function update(Request $request, $id)
    {
        $this->checkGuru($request);

        $dokumentasi = Dokumentasi::find($id);

        if (!$dokumentasi) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        if ($dokumentasi->user_id !== $request->user()->id) {
            return response()->json(['status' => 'error', 'message' => 'Tidak memiliki akses.'], 403);
        }

        $request->validate([
            'judul'     => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'kelas_id'  => ['nullable', 'string'],
            'tanggal'   => ['required', 'date'],
            'foto.*'    => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            $dokumentasi->update([
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'kelas_id'  => $request->kelas_id,
                'tanggal'   => $request->tanggal,
            ]);

            // Kalau ada foto baru dikirim, hapus foto lama & replace
            if ($request->hasFile('foto')) {
                foreach ($dokumentasi->foto as $foto) {
                    Storage::disk('public')->delete($foto->upload_file);
                    $foto->delete();
                }
                foreach ($request->file('foto') as $file) {
                    $path = $file->store('dokumentasi', 'public');
                    DokumentasiFoto::create([
                        'id_dokumentasi' => $dokumentasi->id_dokumentasi,
                        'upload_file'    => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Dokumentasi berhasil diperbarui.',
                'data'    => $this->format($dokumentasi->load('foto')),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // =============================================
    // DELETE /api/dokumentasi/{id}
    // Hanya guru pemilik data
    // =============================================
    public function destroy(Request $request, $id)
    {
        $this->checkGuru($request);

        $dokumentasi = Dokumentasi::with('foto')->find($id);

        if (!$dokumentasi) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        if ($dokumentasi->user_id !== $request->user()->id) {
            return response()->json(['status' => 'error', 'message' => 'Tidak memiliki akses.'], 403);
        }

        // Hapus semua file foto dari storage
        foreach ($dokumentasi->foto as $foto) {
            Storage::disk('public')->delete($foto->upload_file);
        }

        $dokumentasi->delete(); // cascade hapus dokumentasi_foto

        return response()->json([
            'status'  => 'success',
            'message' => 'Dokumentasi berhasil dihapus.',
        ]);
    }

    // =============================================
    // Helper: cek role guru
    // =============================================
    private function checkGuru(Request $request)
    {
        $user = $request->user();
        $roleId = DB::table('roles')->where('nama_role', 'guru')->value('id_role');

        if ($user->role_id !== $roleId) {
            abort(response()->json(['status' => 'error', 'message' => 'Hanya guru yang dapat melakukan aksi ini.'], 403));
        }
    }

    // =============================================
    // Helper: format response
    // =============================================
    private function format($d): array
    {
        return [
            'id_dokumentasi' => $d->id_dokumentasi,
            'judul'          => $d->judul,
            'deskripsi'      => $d->deskripsi,
            'kelas_id'       => $d->kelas_id,
            'kelas_label'    => $d->kelas_id ? 'Kelas ' . $d->kelas_id : 'Semua Kelas',
            'tanggal'        => $d->tanggal,
            'status'         => $d->status,
            'guru'           => $d->guru ? $d->guru->username : null,
            'total_foto'     => $d->foto instanceof \Illuminate\Support\Collection 
                                ? $d->foto->count() 
                                : count((array) $d->foto),
            'foto'           => $d->foto instanceof \Illuminate\Support\Collection
                                ? $d->foto->map(fn($f) => [
                                    'id_foto' => $f->id_foto,
                                    'url'     => asset('storage/' . $f->upload_file),
                                ])->values()
                                : [],
        ];
    }
}