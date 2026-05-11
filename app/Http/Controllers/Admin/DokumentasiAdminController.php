<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class DokumentasiAdminController extends Controller
{
    // =============================================
    // List semua kegiatan dari semua guru
    // =============================================
    public function index(Request $request)
    {
        $search = $request->query('search');
        $kelas = $request->query('kelas');

        $query = Kegiatan::with(['dokumentasi', 'guru'])
            ->where('status', 'aktif');

        if ($search) {
            $query->where('judul', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        if ($kelas) {
            $query->where('kelas_id', $kelas);
        }

        $kegiatans = $query->orderByDesc('tanggal')->get();

        return view('Dashboard_Admin.Dokumentasi.dokumentasi', compact('kegiatans', 'search', 'kelas'));
    }

    // =============================================
    // Detail kegiatan (read-only untuk admin)
    // =============================================
    public function show($id)
    {
        $kegiatan = Kegiatan::with(['dokumentasi', 'guru'])->findOrFail($id);

        return view('Dashboard_Admin.Dokumentasi.dokumentasi-detail', compact('kegiatan'));
    }
}
