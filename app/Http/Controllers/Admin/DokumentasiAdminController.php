<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Kelas;
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

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $query = Kegiatan::with(['dokumentasi', 'guru', 'kelas'])
            ->where('status', 'aktif');

        if ($search) {
            $query->where('judul', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
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

        $latestDokumentasi = Kegiatan::with(['dokumentasi', 'kelas', 'guru'])
            ->latest('tanggal')
            ->first();

        return view('Dashboard_Admin.Dokumentasi.dokumentasi', compact('kegiatans', 'search', 'kelas', 'kelasList', 'latestDokumentasi'));
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