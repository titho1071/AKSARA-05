<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

class AbsensiAdminController extends Controller
{
    public function index(Request $request)
    {
        $tahunPelajaran = TahunPelajaran::orderByDesc('created_at')->get();

        $tapelAktif = TahunPelajaran::where('is_active', 1)->first();

        $tapelId = $request->tapel
            ?? $tapelAktif?->id_tapel
            ?? $tahunPelajaran->first()?->id_tapel;

        $search = $request->search;

        $kelas = Kelas::with('guru')
            ->where('tapel_id', $tapelId)
            ->when($search, fn($q) => $q->where('nama_kelas', 'like', "%{$search}%"))
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return view('Dashboard_Admin.Absensi.absensi-admin', compact(
            'kelas',
            'tahunPelajaran',
            'tapelId',
            'tapelAktif',
            'search'
        ));
    }

    public function recap(Request $request)
    {
        $tahunPelajaran = TahunPelajaran::orderByDesc('created_at')->get();

        $tapelAktif = TahunPelajaran::where('is_active', 1)->first();

        $tapelId = $request->tapel
            ?? $tapelAktif?->id_tapel
            ?? $tahunPelajaran->first()?->id_tapel;

        $search = $request->search;

        // Admin melihat semua kelas (tidak difilter per guru)
        $kelas = Kelas::with('guru')
            ->where('tapel_id', $tapelId)
            ->when($search, fn($q) => $q->where('nama_kelas', 'like', "%{$search}%"))
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return view('Dashboard_Admin.Absensi.rekap-absensi', compact(
            'kelas',
            'tahunPelajaran',
            'tapelId',
            'tapelAktif',
            'search'
        ));
    }
}
