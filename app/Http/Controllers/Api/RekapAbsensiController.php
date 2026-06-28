<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapAbsensiController extends Controller
{
    // =====================================================
    // REKAP ABSENSI - ADMIN
    // =====================================================

    /**
     * [ADMIN] Daftar semua kelas untuk rekap absensi
     * GET /api/rekap-absensi/admin?tapel=&search=&per_page=
     */
    public function indexAdmin(Request $request)
    {
        $tahunPelajaran = TahunPelajaran::orderByDesc('created_at')->get();
        $tapelAktif     = TahunPelajaran::where('is_active', 1)->first();

        $tapelId = $request->tapel
            ?? $tapelAktif?->id_tapel
            ?? $tahunPelajaran->first()?->id_tapel;

        $search = $request->search;

        $kelas = Kelas::with(['guru', 'tahunPelajaran'])
            ->where('tapel_id', $tapelId)
            ->when($search, fn($q) => $q->where('nama_kelas', 'like', "%{$search}%"))
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar kelas rekap absensi (admin)',
            'data'    => $kelas,
            'filter'  => [
                'tapel_id'        => $tapelId,
                'tapel_aktif'     => $tapelAktif,
                'tahun_pelajaran' => $tahunPelajaran,
                'search'          => $search,
            ],
        ]);
    }

    /**
     * [ADMIN] Rekap absensi 1 bulan
     * GET /api/rekap-absensi/admin/1-bulan?kelas_id=1&bulan=7
     */
    public function preview1BulanAdmin(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id_kelas',
            'bulan'    => 'required|integer|min:1|max:12',
        ]);

        $kelas = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])
            ->findOrFail($request->kelas_id);

        $bulan      = (int) $request->bulan;
        $tahunParts = explode('/', $kelas->tahunPelajaran->tahun_pelajaran);
        $tahunAwal  = (int) $tahunParts[0];
        $tahunAkhir = (int) explode('-', $tahunParts[1])[0];
        $tahun      = $bulan >= 7 ? $tahunAwal : $tahunAkhir;

        $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $endDate   = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        $data = $this->buildRekapData($kelas, $startDate, $endDate);

        return response()->json([
            'status'  => 'success',
            'message' => 'Rekap absensi 1 bulan',
            'data'    => [
                'kelas'      => [
                    'id_kelas'        => $kelas->id_kelas,
                    'nama_kelas'      => $kelas->nama_kelas,
                    'tingkat'         => $kelas->tingkat,
                    'guru'            => $kelas->guru,
                    'tahun_pelajaran' => $kelas->tahunPelajaran,
                ],
                'periode'    => [
                    'bulan'      => $bulan,
                    'bulan_label'=> Carbon::create()->month($bulan)->translatedFormat('F'),
                    'tahun'      => $tahun,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date'   => $endDate->format('Y-m-d'),
                ],
                'siswa'      => $data,
                'total_siswa'=> count($data),
            ],
        ]);
    }

    /**
     * [ADMIN] Rekap absensi 3 bulan (tribulan)
     * GET /api/rekap-absensi/admin/tribulan?kelas_id=1&bulan_awal=7&bulan_akhir=9
     */
    public function previewTribulanAdmin(Request $request)
    {
        $request->validate([
            'kelas_id'   => 'required|integer|exists:kelas,id_kelas',
            'bulan_awal' => 'required|integer|min:1|max:12',
            'bulan_akhir'=> 'required|integer|min:1|max:12|gte:bulan_awal',
        ]);

        $kelas      = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])->findOrFail($request->kelas_id);
        $bulanAwal  = (int) $request->bulan_awal;
        $bulanAkhir = (int) $request->bulan_akhir;

        $tahunParts = explode('/', $kelas->tahunPelajaran->tahun_pelajaran);
        $tahunAwal  = (int) $tahunParts[0];
        $tahunAkhir = (int) explode('-', $tahunParts[1])[0];
        $tahun      = $bulanAwal >= 7 ? $tahunAwal : $tahunAkhir;

        $startDate = Carbon::create($tahun, $bulanAwal, 1)->startOfMonth();
        $endDate   = Carbon::create($tahun, $bulanAkhir, 1)->endOfMonth();

        $data = $this->buildRekapData($kelas, $startDate, $endDate);

        return response()->json([
            'status'  => 'success',
            'message' => 'Rekap absensi tribulan',
            'data'    => [
                'kelas'      => [
                    'id_kelas'        => $kelas->id_kelas,
                    'nama_kelas'      => $kelas->nama_kelas,
                    'tingkat'         => $kelas->tingkat,
                    'guru'            => $kelas->guru,
                    'tahun_pelajaran' => $kelas->tahunPelajaran,
                ],
                'periode'    => [
                    'bulan_awal'        => $bulanAwal,
                    'bulan_awal_label'  => Carbon::create()->month($bulanAwal)->translatedFormat('F'),
                    'bulan_akhir'       => $bulanAkhir,
                    'bulan_akhir_label' => Carbon::create()->month($bulanAkhir)->translatedFormat('F'),
                    'tahun'             => $tahun,
                    'start_date'        => $startDate->format('Y-m-d'),
                    'end_date'          => $endDate->format('Y-m-d'),
                ],
                'siswa'      => $data,
                'total_siswa'=> count($data),
            ],
        ]);
    }

    /**
     * [ADMIN] Rekap absensi per semester
     * GET /api/rekap-absensi/admin/semester?kelas_id=1
     */
    public function previewSemesterAdmin(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id_kelas',
        ]);

        $kelas      = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])->findOrFail($request->kelas_id);
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

        $data = $this->buildRekapData($kelas, $startDate, $endDate);

        return response()->json([
            'status'  => 'success',
            'message' => "Rekap absensi semester {$semester}",
            'data'    => [
                'kelas'      => [
                    'id_kelas'        => $kelas->id_kelas,
                    'nama_kelas'      => $kelas->nama_kelas,
                    'tingkat'         => $kelas->tingkat,
                    'guru'            => $kelas->guru,
                    'tahun_pelajaran' => $kelas->tahunPelajaran,
                ],
                'periode'    => [
                    'semester'   => $semester,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date'   => $endDate->format('Y-m-d'),
                ],
                'siswa'      => $data,
                'total_siswa'=> count($data),
            ],
        ]);
    }

    /**
     * [ADMIN] Rekap absensi 1 tahun penuh
     * GET /api/rekap-absensi/admin/tahunan?kelas_id=1
     */
    public function previewTahunanAdmin(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id_kelas',
        ]);

        $kelas      = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])->findOrFail($request->kelas_id);
        $tahunParts = explode('/', $kelas->tahunPelajaran->tahun_pelajaran);
        $tahunAwal  = (int) $tahunParts[0];
        $tahunAkhir = (int) explode('-', $tahunParts[1])[0];

        $startDate = Carbon::create($tahunAwal, 7, 1)->startOfMonth();
        $endDate   = Carbon::create($tahunAkhir, 6, 1)->endOfMonth();

        $data = $this->buildRekapData($kelas, $startDate, $endDate);

        return response()->json([
            'status'  => 'success',
            'message' => "Rekap absensi tahun {$tahunAwal}/{$tahunAkhir}",
            'data'    => [
                'kelas'      => [
                    'id_kelas'        => $kelas->id_kelas,
                    'nama_kelas'      => $kelas->nama_kelas,
                    'tingkat'         => $kelas->tingkat,
                    'guru'            => $kelas->guru,
                    'tahun_pelajaran' => $kelas->tahunPelajaran,
                ],
                'periode'    => [
                    'tahun_awal'  => $tahunAwal,
                    'tahun_akhir' => $tahunAkhir,
                    'start_date'  => $startDate->format('Y-m-d'),
                    'end_date'    => $endDate->format('Y-m-d'),
                ],
                'siswa'      => $data,
                'total_siswa'=> count($data),
            ],
        ]);
    }

    // =====================================================
    // REKAP ABSENSI - GURU
    // =====================================================

    /**
     * [GURU] Daftar kelas wali milik guru yang login
     * GET /api/rekap-absensi/guru?tapel=&search=
     */
    public function indexGuru(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->first();

        if (!$guru) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data guru tidak ditemukan untuk user ini.',
            ], 404);
        }

        $tahunPelajaran = TahunPelajaran::orderByDesc('created_at')->get();
        $tapelAktif     = TahunPelajaran::where('is_active', 1)->first();

        $tapelId = $request->tapel
            ?? $tapelAktif?->id_tapel
            ?? $tahunPelajaran->first()?->id_tapel;

        $search = $request->search;

        $kelas = Kelas::with(['guru', 'tahunPelajaran'])
            ->where('tapel_id', $tapelId)
            ->where('guru_id', $guru->id_guru)
            ->when($search, fn($q) => $q->where('nama_kelas', 'like', "%{$search}%"))
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar kelas rekap absensi (guru)',
            'guru'    => [
                'id_guru' => $guru->id_guru,
                'nama'    => $guru->nama,
            ],
            'data'    => $kelas,
        ]);
    }

    /**
     * [GURU] Rekap absensi 1 bulan
     * GET /api/rekap-absensi/guru/1-bulan?kelas_id=1&bulan=7
     */
    public function preview1BulanGuru(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id_kelas',
            'bulan'    => 'required|integer|min:1|max:12',
        ]);

        $kelas = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])->findOrFail($request->kelas_id);

        $bulan      = (int) $request->bulan;
        $tahunParts = explode('/', $kelas->tahunPelajaran->tahun_pelajaran);
        $tahunAwal  = (int) $tahunParts[0];
        $tahunAkhir = (int) explode('-', $tahunParts[1])[0];
        $tahun      = $bulan >= 7 ? $tahunAwal : $tahunAkhir;

        $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $endDate   = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        $data = $this->buildRekapData($kelas, $startDate, $endDate);

        return response()->json([
            'status'  => 'success',
            'message' => 'Rekap absensi 1 bulan (guru)',
            'data'    => [
                'kelas'      => [
                    'id_kelas'        => $kelas->id_kelas,
                    'nama_kelas'      => $kelas->nama_kelas,
                    'tingkat'         => $kelas->tingkat,
                    'guru'            => $kelas->guru,
                    'tahun_pelajaran' => $kelas->tahunPelajaran,
                ],
                'periode'    => [
                    'bulan'       => $bulan,
                    'bulan_label' => Carbon::create()->month($bulan)->translatedFormat('F'),
                    'tahun'       => $tahun,
                    'start_date'  => $startDate->format('Y-m-d'),
                    'end_date'    => $endDate->format('Y-m-d'),
                ],
                'siswa'      => $data,
                'total_siswa'=> count($data),
            ],
        ]);
    }

    /**
     * [GURU] Rekap absensi tribulan
     * GET /api/rekap-absensi/guru/tribulan?kelas_id=1&bulan_awal=7&bulan_akhir=9
     */
    public function previewTribulanGuru(Request $request)
    {
        $request->validate([
            'kelas_id'   => 'required|integer|exists:kelas,id_kelas',
            'bulan_awal' => 'required|integer|min:1|max:12',
            'bulan_akhir'=> 'required|integer|min:1|max:12|gte:bulan_awal',
        ]);

        $kelas      = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])->findOrFail($request->kelas_id);
        $bulanAwal  = (int) $request->bulan_awal;
        $bulanAkhir = (int) $request->bulan_akhir;

        $tahunParts = explode('/', $kelas->tahunPelajaran->tahun_pelajaran);
        $tahunAwal  = (int) $tahunParts[0];
        $tahunAkhir = (int) explode('-', $tahunParts[1])[0];
        $tahun      = $bulanAwal >= 7 ? $tahunAwal : $tahunAkhir;

        $startDate = Carbon::create($tahun, $bulanAwal, 1)->startOfMonth();
        $endDate   = Carbon::create($tahun, $bulanAkhir, 1)->endOfMonth();

        $data = $this->buildRekapData($kelas, $startDate, $endDate);

        return response()->json([
            'status'  => 'success',
            'message' => 'Rekap absensi tribulan (guru)',
            'data'    => [
                'kelas'      => [
                    'id_kelas'        => $kelas->id_kelas,
                    'nama_kelas'      => $kelas->nama_kelas,
                    'tingkat'         => $kelas->tingkat,
                    'guru'            => $kelas->guru,
                    'tahun_pelajaran' => $kelas->tahunPelajaran,
                ],
                'periode'    => [
                    'bulan_awal'        => $bulanAwal,
                    'bulan_awal_label'  => Carbon::create()->month($bulanAwal)->translatedFormat('F'),
                    'bulan_akhir'       => $bulanAkhir,
                    'bulan_akhir_label' => Carbon::create()->month($bulanAkhir)->translatedFormat('F'),
                    'tahun'             => $tahun,
                    'start_date'        => $startDate->format('Y-m-d'),
                    'end_date'          => $endDate->format('Y-m-d'),
                ],
                'siswa'      => $data,
                'total_siswa'=> count($data),
            ],
        ]);
    }

    /**
     * [GURU] Rekap absensi semester
     * GET /api/rekap-absensi/guru/semester?kelas_id=1
     */
    public function previewSemesterGuru(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id_kelas',
        ]);

        $kelas      = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])->findOrFail($request->kelas_id);
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

        $data = $this->buildRekapData($kelas, $startDate, $endDate);

        return response()->json([
            'status'  => 'success',
            'message' => "Rekap absensi semester {$semester} (guru)",
            'data'    => [
                'kelas'      => [
                    'id_kelas'        => $kelas->id_kelas,
                    'nama_kelas'      => $kelas->nama_kelas,
                    'tingkat'         => $kelas->tingkat,
                    'guru'            => $kelas->guru,
                    'tahun_pelajaran' => $kelas->tahunPelajaran,
                ],
                'periode'    => [
                    'semester'   => $semester,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date'   => $endDate->format('Y-m-d'),
                ],
                'siswa'      => $data,
                'total_siswa'=> count($data),
            ],
        ]);
    }

    /**
     * [GURU] Rekap absensi 1 tahun
     * GET /api/rekap-absensi/guru/tahunan?kelas_id=1
     */
    public function previewTahunanGuru(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id_kelas',
        ]);

        $kelas      = Kelas::with(['siswa', 'tahunPelajaran', 'guru'])->findOrFail($request->kelas_id);
        $tahunParts = explode('/', $kelas->tahunPelajaran->tahun_pelajaran);
        $tahunAwal  = (int) $tahunParts[0];
        $tahunAkhir = (int) explode('-', $tahunParts[1])[0];

        $startDate = Carbon::create($tahunAwal, 7, 1)->startOfMonth();
        $endDate   = Carbon::create($tahunAkhir, 6, 1)->endOfMonth();

        $data = $this->buildRekapData($kelas, $startDate, $endDate);

        return response()->json([
            'status'  => 'success',
            'message' => "Rekap absensi tahunan {$tahunAwal}/{$tahunAkhir} (guru)",
            'data'    => [
                'kelas'      => [
                    'id_kelas'        => $kelas->id_kelas,
                    'nama_kelas'      => $kelas->nama_kelas,
                    'tingkat'         => $kelas->tingkat,
                    'guru'            => $kelas->guru,
                    'tahun_pelajaran' => $kelas->tahunPelajaran,
                ],
                'periode'    => [
                    'tahun_awal'  => $tahunAwal,
                    'tahun_akhir' => $tahunAkhir,
                    'start_date'  => $startDate->format('Y-m-d'),
                    'end_date'    => $endDate->format('Y-m-d'),
                ],
                'siswa'      => $data,
                'total_siswa'=> count($data),
            ],
        ]);
    }

    // =====================================================
    // HELPER PRIVATE
    // =====================================================

    /**
     * Build rekap data array per siswa dalam rentang tanggal tertentu
     */
    private function buildRekapData($kelas, $startDate, $endDate): array
    {
        $data = [];
        foreach ($kelas->siswa as $siswa) {
            $absensi = Absensi::where('siswa_id', $siswa->id_siswa)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            $data[] = [
                'nis'           => $siswa->nis,
                'nama'          => $siswa->nama,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'hadir'         => $absensi->where('status_kehadiran', 'H')->count(),
                'sakit'         => $absensi->where('status_kehadiran', 'S')->count(),
                'izin'          => $absensi->where('status_kehadiran', 'I')->count(),
                'alpha'         => $absensi->where('status_kehadiran', 'A')->count(),
                'total_hari'    => $absensi->count(),
            ];
        }
        return $data;
    }
}
