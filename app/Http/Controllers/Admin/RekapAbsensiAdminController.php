<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
use App\Models\Absensi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RekapAbsensiAdminController extends Controller
{
    public function index(Request $request)
    {
        $tahunPelajaran = TahunPelajaran::orderByDesc('created_at')->get();

        $tapelAktif = TahunPelajaran::where('is_active', 1)->first();

        $tapelId = $request->tapel
            ?? $tapelAktif?->id_tapel
            ?? $tahunPelajaran->first()?->id_tapel;

        $search = $request->search;

        // Admin: tampilkan SEMUA kelas, tidak difilter by guru_id
        $kelas = Kelas::with(['guru', 'tahunPelajaran'])
            ->where('tapel_id', $tapelId)
            ->when($search, fn($q) =>
                $q->where('nama_kelas', 'like', "%{$search}%")
            )
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate(10)
            ->withQueryString();

        return view(
            'Dashboard_Admin.Absensi.rekap-absensi',
            compact('kelas', 'tahunPelajaran', 'tapelId', 'tapelAktif', 'search')
        );
    }

    public function preview1Bulan(Request $request)
    {
        $kelas = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])
            ->findOrFail($request->kelas_id);

        $bulan = (int) $request->bulan;

        $tahunParts = explode('/', $kelas->tahunPelajaran->tahun_pelajaran);
        $tahunAwal  = (int) $tahunParts[0];
        $tahunAkhir = (int) explode('-', $tahunParts[1])[0];

        $tahun = $bulan >= 7 ? $tahunAwal : $tahunAkhir;

        $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $endDate   = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        $data = [];
        foreach ($kelas->siswa as $siswa) {
            $absensi = Absensi::where('siswa_id', $siswa->id_siswa)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            $data[] = [
                'nis'   => $siswa->nis,
                'nama'  => $siswa->nama,
                'jk'    => $siswa->jenis_kelamin,
                'hadir' => $absensi->where('status_kehadiran', 'H')->count(),
                'sakit' => $absensi->where('status_kehadiran', 'S')->count(),
                'izin'  => $absensi->where('status_kehadiran', 'I')->count(),
                'alpha' => $absensi->where('status_kehadiran', 'A')->count(),
            ];
        }

        $pdf = Pdf::loadView(
            'Dashboard_Guru.Absensi.pdf.rekap-1bulan', // reuse template guru
            compact('kelas', 'data', 'bulan', 'tahun')
        );

        return $pdf->stream(
            'rekap-absensi-' .
            strtolower(Carbon::create()->month($bulan)->translatedFormat('F')) .
            '-' . $kelas->nama_kelas . '.pdf'
        );
    }

    public function previewTribulan(Request $request)
    {
        $kelas = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])
            ->findOrFail($request->kelas_id);

        $bulanAwal  = (int) $request->bulan_awal;
        $bulanAkhir = (int) $request->bulan_akhir;

        $tahunParts = explode('/', $kelas->tahunPelajaran->tahun_pelajaran);
        $tahunAwal  = (int) $tahunParts[0];
        $tahunAkhir = (int) explode('-', $tahunParts[1])[0];

        $tahun = $bulanAwal >= 7 ? $tahunAwal : $tahunAkhir;

        $startDate = Carbon::create($tahun, $bulanAwal, 1)->startOfMonth();
        $endDate   = Carbon::create($tahun, $bulanAkhir, 1)->endOfMonth();

        $data = [];
        foreach ($kelas->siswa as $siswa) {
            $absensi = Absensi::where('siswa_id', $siswa->id_siswa)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            $data[] = [
                'nis'   => $siswa->nis,
                'nama'  => $siswa->nama,
                'jk'    => $siswa->jenis_kelamin,
                'hadir' => $absensi->where('status_kehadiran', 'H')->count(),
                'sakit' => $absensi->where('status_kehadiran', 'S')->count(),
                'izin'  => $absensi->where('status_kehadiran', 'I')->count(),
                'alpha' => $absensi->where('status_kehadiran', 'A')->count(),
            ];
        }

        $pdf = Pdf::loadView(
            'Dashboard_Guru.Absensi.pdf.rekap-3bulan',
            compact('kelas', 'data', 'bulanAwal', 'bulanAkhir', 'tahun')
        );

        $labelAwal  = strtolower(Carbon::create()->month($bulanAwal)->translatedFormat('F'));
        $labelAkhir = strtolower(Carbon::create()->month($bulanAkhir)->translatedFormat('F'));

        return $pdf->stream(
            "rekap-absensi-{$labelAwal}-{$labelAkhir}-{$kelas->nama_kelas}.pdf"
        );
    }

    public function previewSemester(Request $request)
    {
        $kelas = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])
            ->findOrFail($request->kelas_id);

        $semester   = $kelas->tahunPelajaran->semester;
        $tahunParts = explode('/', $kelas->tahunPelajaran->tahun_pelajaran);
        $tahunAwal  = (int) $tahunParts[0];
        $tahunAkhir = (int) explode('-', $tahunParts[1])[0];

        if ($semester === 'Ganjil') {
            $startDate = Carbon::create($tahunAwal, 7, 1)->startOfMonth();
            $endDate   = Carbon::create($tahunAwal, 12, 1)->endOfMonth();
        } else {
            $startDate = Carbon::create($tahunAkhir, 1, 1)->startOfMonth();
            $endDate   = Carbon::create($tahunAkhir, 6, 1)->endOfMonth();
        }

        $data = [];
        foreach ($kelas->siswa as $siswa) {
            $absensi = Absensi::where('siswa_id', $siswa->id_siswa)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            $data[] = [
                'nis'   => $siswa->nis,
                'nama'  => $siswa->nama,
                'jk'    => $siswa->jenis_kelamin,
                'hadir' => $absensi->where('status_kehadiran', 'H')->count(),
                'sakit' => $absensi->where('status_kehadiran', 'S')->count(),
                'izin'  => $absensi->where('status_kehadiran', 'I')->count(),
                'alpha' => $absensi->where('status_kehadiran', 'A')->count(),
            ];
        }

        $pdf = Pdf::loadView(
            'Dashboard_Guru.Absensi.pdf.rekap-semester',
            compact('kelas', 'data', 'semester', 'tahunAwal', 'tahunAkhir')
        );

        return $pdf->stream(
            "rekap-absensi-semester-{$semester}-{$kelas->nama_kelas}.pdf"
        );
    }

    public function previewTahun(Request $request)
    {
        $kelas = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])
            ->findOrFail($request->kelas_id);

        $tahunParts = explode('/', $kelas->tahunPelajaran->tahun_pelajaran);
        $tahunAwal  = (int) $tahunParts[0];
        $tahunAkhir = (int) explode('-', $tahunParts[1])[0];

        $startDate = Carbon::create($tahunAwal, 7, 1)->startOfMonth();
        $endDate   = Carbon::create($tahunAkhir, 6, 1)->endOfMonth();

        $data = [];
        foreach ($kelas->siswa as $siswa) {
            $absensi = Absensi::where('siswa_id', $siswa->id_siswa)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            $data[] = [
                'nis'   => $siswa->nis,
                'nama'  => $siswa->nama,
                'jk'    => $siswa->jenis_kelamin,
                'hadir' => $absensi->where('status_kehadiran', 'H')->count(),
                'sakit' => $absensi->where('status_kehadiran', 'S')->count(),
                'izin'  => $absensi->where('status_kehadiran', 'I')->count(),
                'alpha' => $absensi->where('status_kehadiran', 'A')->count(),
            ];
        }

        $pdf = Pdf::loadView(
            'Dashboard_Guru.Absensi.pdf.rekap-tahun',
            compact('kelas', 'data', 'tahunAwal', 'tahunAkhir')
        );

        return $pdf->stream(
            "rekap-absensi-{$tahunAwal}-{$tahunAkhir}-{$kelas->nama_kelas}.pdf"
        );
    }
}