<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Dokumentasi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DokumentasiGuruController extends Controller
{
    // =============================================
    // List kegiatan milik guru yang login
    // =============================================
    public function index(Request $request)
    {
        $search = $request->query('search');
        $kelas  = $request->query('kelas');

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $query = Kegiatan::with(['dokumentasi', 'kelas'])
            ->where('user_id', Auth::id())
            ->where('status', 'aktif');

        if ($search) {
            $query->where('judul', 'like', "%{$search}%");
        }

        if ($kelas !== null) {
            if ($kelas === 'semua_kelas') {
                $query->where(function ($q) {
                    $q->whereNull('kelas_id')
                      ->orWhere('kelas_id', 'semua_kelas');
                });
            } else {
                $query->where('kelas_id', $kelas);
            }
        }

        $kegiatans = $query->orderByDesc('tanggal')->get();

        return view('Dashboard_Guru.Dokumentasi.dokumentasi-guru', compact(
            'kegiatans',
            'search',
            'kelas',
            'kelasList'
        ));
    }

    // =============================================
    // Form tambah kegiatan
    // =============================================
    public function create()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('Dashboard_Guru.Dokumentasi.dokumentasi-guru-create', compact('kelasList'));
    }

    // =============================================
    // Simpan kegiatan + foto
    // =============================================
    public function store(Request $request)
    {
        $request->validate([
            'judul'     => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'tanggal'   => ['required', 'date'],
            'kelas_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'semua_kelas' && !Kelas::where('id_kelas', $value)->exists()) {
                        $fail('Kelas tidak valid.');
                    }
                },
            ],
            'foto'      => ['required', 'array', 'min:1', 'max:10'],
            'foto.*'    => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        DB::beginTransaction();

        try {

            $kegiatan = Kegiatan::create([
                'user_id'   => Auth::id(),
                'kelas_id'  => $request->kelas_id,
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tanggal'   => $request->tanggal,
                'status'    => 'aktif',
            ]);

            foreach ($request->file('foto') as $file) {

                $path = $file->store('kegiatan', 'public');

                Dokumentasi::create([
                    'id_kegiatan' => $kegiatan->id_kegiatan,
                    'foto'        => $path,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('guru.dokumentasi.show', $kegiatan->id_kegiatan)
                ->with('success', 'Kegiatan berhasil ditambahkan.');

        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error($e);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    // =============================================
    // Detail kegiatan
    // =============================================
    public function show($id)
    {
        $kegiatan = Kegiatan::with([
            'dokumentasi',
            'guru',
            'kelas'
        ])->findOrFail($id);

        if ($kegiatan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('Dashboard_Guru.Dokumentasi.dokumentasi-guru-detail', compact('kegiatan'));
    }

    // =============================================
    // Form edit kegiatan
    // =============================================
    public function edit($id)
    {
        $kegiatan = Kegiatan::with(['dokumentasi', 'kelas'])->findOrFail($id);

        if ($kegiatan->user_id !== Auth::id()) {
            abort(403);
        }

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('Dashboard_Guru.Dokumentasi.dokumentasi-guru-edit', compact(
            'kegiatan',
            'kelasList'
        ));
    }

    // =============================================
    // Update kegiatan + foto
    // =============================================
    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::with(['dokumentasi', 'kelas'])->findOrFail($id);

        if ($kegiatan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'judul'     => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'tanggal'   => ['required', 'date'],
            'kelas_id' => [
                'required',
                function ($attribute, $value, $fail) {

                    if ($value === 'semua_kelas') {
                        return;
                    }

                    if (!Kelas::where('id_kelas', $value)->exists()) {
                        $fail('Kelas tidak valid.');
                    }
                }
            ],
            'foto.*'    => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        // Validasi total maksimal 10 foto
        $totalFoto = $kegiatan->dokumentasi->count();
        $fotoBaru  = count($request->file('foto') ?? []);

        if (($totalFoto + $fotoBaru) > 10) {
            return back()->withErrors([
                'foto' => 'Total maksimal foto adalah 10 file.'
            ])->withInput();
        }

        DB::beginTransaction();

        try {

            $kegiatan->update([
                'kelas_id'  => $request->kelas_id,
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tanggal'   => $request->tanggal,
            ]);

            if ($request->hasFile('foto')) {

                foreach ($request->file('foto') as $file) {

                    $path = $file->store('kegiatan', 'public');

                    Dokumentasi::create([
                        'id_kegiatan' => $kegiatan->id_kegiatan,
                        'foto'        => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('guru.dokumentasi.index')
                ->with('success', 'Kegiatan berhasil diperbarui.');

        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error($e);

            return back()->with(
                'error',
                'Terjadi kesalahan saat memperbarui data.'
            );
        }
    }

    // =============================================
    // Hapus kegiatan beserta semua foto
    // =============================================
    public function destroy($id)
    {
        $kegiatan = Kegiatan::with(['dokumentasi'])->findOrFail($id);

        // Validasi pemilik
        if ($kegiatan->user_id !== Auth::id()) {
            abort(403);
        }

        DB::beginTransaction();

        try {

            // Hapus semua file foto dari storage
            foreach ($kegiatan->dokumentasi as $dok) {

                if (
                    $dok->foto &&
                    Storage::disk('public')->exists($dok->foto)
                ) {
                    Storage::disk('public')->delete($dok->foto);
                }

                // Hapus record dokumentasi
                $dok->delete();
            }

            // Hapus kegiatan
            $kegiatan->delete();

            DB::commit();

            return redirect()
                ->route('guru.dokumentasi.index')
                ->with('success', 'Kegiatan berhasil dihapus.');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with(
                'error',
                'Terjadi kesalahan: ' . $e->getMessage()
            );
        }
    }


    // =============================================
    // Hapus 1 foto individual (AJAX)
    // =============================================
    public function destroyFoto($idDokumentasi)
    {
        $dok = Dokumentasi::findOrFail($idDokumentasi);

        $kegiatan = Kegiatan::findOrFail($dok->id_kegiatan);

        // Validasi pemilik
        if ($kegiatan->user_id !== Auth::id()) {

            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak memiliki akses.'
            ], 403);
        }

        try {

            // Cegah semua foto habis
            if ($kegiatan->dokumentasi()->count() <= 1) {

                return response()->json([
                    'status'  => 'error',
                    'message' => 'Minimal harus ada 1 foto dokumentasi.'
                ], 422);
            }

            // Hapus file storage
            if (
                $dok->foto &&
                Storage::disk('public')->exists($dok->foto)
            ) {
                Storage::disk('public')->delete($dok->foto);
            }

            // Hapus database
            $dok->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Foto berhasil dihapus.'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}