<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
use App\Models\Absensi;
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

    public function pilihBulan($id)
    {
        $kelas = Kelas::with([
            'guru',
            'tahunPelajaran'
        ])->findOrFail($id);

        return view(
            'Dashboard_Admin.Absensi.pilih-bulan-admin',
            compact('kelas')
        );
    }

    public function detail($id, $bulan)
    {
        $kelas = Kelas::with([
            'guru',
            'tahunPelajaran',
            'siswa'
        ])->findOrFail($id);

        $bulanMap = [
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12,
        ];

        $bulanAngka = $bulanMap[strtolower($bulan)] ?? now()->month;

        $jumlahHari = cal_days_in_month(
            CAL_GREGORIAN,
            $bulanAngka,
            now()->year
        );

        $siswaIds = $kelas->siswa->pluck('id_siswa');

        $absensi = Absensi::whereIn('siswa_id', $siswaIds)
            ->whereMonth('tanggal', $bulanAngka)
            ->get();

        $absensiBulan = [];

        foreach ($absensi as $item) {
            $hari = \Carbon\Carbon::parse($item->tanggal)->day;

            $absensiBulan[$item->siswa_id][$hari]
                = $item->status_kehadiran;
        }

        return view(
            'Dashboard_Admin.Absensi.detail-absensi-admin',
            compact(
                'kelas',
                'bulan',
                'bulanAngka',
                'jumlahHari',
                'absensiBulan'
            )
        );
    }
}
