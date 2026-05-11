<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DokumentasiGuruController extends Controller
{
    // =============================================
    // List kegiatan milik guru yang login
    // =============================================
    public function index(Request $request)
    {
        $search = $request->query('search');
        $kelas = $request->query('kelas');

        $query = Kegiatan::with('dokumentasi')
            ->where('user_id', Auth::id())
            ->where('status', 'aktif');

        if ($search) {
            $query->where('judul', 'like', "%{$search}%");
        }

        if ($kelas) {
            $query->where('kelas_id', $kelas);
        }

        $kegiatans = $query->orderByDesc('tanggal')->get();

        return view('Dashboard_Guru.Dokumentasi.dokumentasi-guru', compact('kegiatans', 'search', 'kelas'));
    }

    // =============================================
    // Form tambah kegiatan
    // =============================================
    public function create()
    {
        return view('Dashboard_Guru.Dokumentasi.dokumentasi-guru-create');
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
            'kelas_id'  => ['nullable', 'string', 'max:50'],
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
            return redirect()->route('guru.dokumentasi.index')
                ->with('success', 'Kegiatan berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =============================================
    // Detail kegiatan
    // =============================================
    public function show($id)
    {
        $kegiatan = Kegiatan::with(['dokumentasi', 'guru'])->findOrFail($id);

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
        $kegiatan = Kegiatan::with('dokumentasi')->findOrFail($id);

        if ($kegiatan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('Dashboard_Guru.Dokumentasi.dokumentasi-guru-edit', compact('kegiatan'));
    }

    // =============================================
    // Update kegiatan + foto
    // =============================================
    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::with('dokumentasi')->findOrFail($id);

        if ($kegiatan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'judul'     => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'tanggal'   => ['required', 'date'],
            'kelas_id'  => ['nullable', 'string', 'max:50'],
            'foto.*'    => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

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
            return redirect()->route('guru.dokumentasi.index')
                ->with('success', 'Kegiatan berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =============================================
    // Hapus kegiatan beserta semua foto
    // =============================================
    public function destroy($id)
    {
        $kegiatan = Kegiatan::with('dokumentasi')->findOrFail($id);

        if ($kegiatan->user_id !== Auth::id()) {
            abort(403);
        }

        foreach ($kegiatan->dokumentasi as $dok) {
            Storage::disk('public')->delete($dok->foto);
        }

        $kegiatan->delete();

        return redirect()->route('guru.dokumentasi.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    // =============================================
    // Hapus 1 foto individual (AJAX dari halaman edit)
    // =============================================
    public function destroyFoto($idDokumentasi)
    {
        $dok      = Dokumentasi::findOrFail($idDokumentasi);
        $kegiatan = Kegiatan::findOrFail($dok->id_kegiatan);

        if ($kegiatan->user_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Tidak memiliki akses.'], 403);
        }

        Storage::disk('public')->delete($dok->foto);
        $dok->delete();

        return response()->json(['status' => 'success', 'message' => 'Foto berhasil dihapus.']);
    }
}