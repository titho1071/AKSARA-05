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
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    // =====================================================
    // ABSENSI - ADMIN
    // =====================================================

    /**
     * [ADMIN] Daftar semua kelas beserta info guru (dengan filter tapel & search)
     */
    public function indexAdmin(Request $request)
    {
        $tahunPelajaran = TahunPelajaran::orderByDesc('created_at')->get();
        $tapelAktif     = TahunPelajaran::where('is_active', 1)->first();

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

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar kelas untuk absensi (admin)',
            'data'    => $kelas,
            'filter'  => [
                'tapel_id'       => $tapelId,
                'tapel_aktif'    => $tapelAktif,
                'tahun_pelajaran' => $tahunPelajaran,
                'search'         => $search,
            ],
        ]);
    }

    /**
     * [ADMIN] Detail absensi per kelas per bulan
     * GET /api/absensi/admin/kelas/{id}/{bulan}
     * Contoh bulan: januari, februari, ...
     */
    public function detailAdmin($id, $bulan)
    {
        $kelas = Kelas::with(['guru', 'tahunPelajaran', 'siswa'])->findOrFail($id);

        $bulanMap = [
            'januari'  => 1,  'februari' => 2,  'maret'    => 3,
            'april'    => 4,  'mei'       => 5,  'juni'     => 6,
            'juli'     => 7,  'agustus'   => 8,  'september'=> 9,
            'oktober'  => 10, 'november'  => 11, 'desember' => 12,
        ];

        $bulanAngka = $bulanMap[strtolower($bulan)] ?? now()->month;
        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulanAngka, now()->year);
        $siswaIds   = $kelas->siswa->pluck('id_siswa');

        $absensiRaw = Absensi::whereIn('siswa_id', $siswaIds)
            ->whereMonth('tanggal', $bulanAngka)
            ->get();

        // Format data absensi per siswa per hari
        $absensiBulan = [];
        foreach ($absensiRaw as $item) {
            $hari = Carbon::parse($item->tanggal)->day;
            $absensiBulan[$item->siswa_id][$hari] = $item->status_kehadiran;
        }

        // Rekapitulasi per siswa
        $rekap = $kelas->siswa->map(function ($siswa) use ($absensiRaw, $absensiBulan) {
            $absensiSiswa = $absensiRaw->where('siswa_id', $siswa->id_siswa);
            return [
                'id_siswa'       => $siswa->id_siswa,
                'nis'            => $siswa->nis,
                'nama'           => $siswa->nama,
                'jenis_kelamin'  => $siswa->jenis_kelamin,
                'absensi_per_hari' => $absensiBulan[$siswa->id_siswa] ?? [],
                'rekap'          => [
                    'hadir' => $absensiSiswa->where('status_kehadiran', 'H')->count(),
                    'sakit' => $absensiSiswa->where('status_kehadiran', 'S')->count(),
                    'izin'  => $absensiSiswa->where('status_kehadiran', 'I')->count(),
                    'alpha' => $absensiSiswa->where('status_kehadiran', 'A')->count(),
                ],
            ];
        });

        return response()->json([
            'status'  => 'success',
            'message' => "Detail absensi kelas {$kelas->nama_kelas} bulan {$bulan}",
            'data'    => [
                'kelas'       => [
                    'id_kelas'   => $kelas->id_kelas,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tingkat'    => $kelas->tingkat,
                    'guru'       => $kelas->guru,
                    'tahun_pelajaran' => $kelas->tahunPelajaran,
                ],
                'bulan'       => $bulan,
                'bulan_angka' => $bulanAngka,
                'jumlah_hari' => $jumlahHari,
                'siswa'       => $rekap,
            ],
        ]);
    }

    // =====================================================
    // ABSENSI - GURU
    // =====================================================

    /**
     * [GURU] Daftar kelas wali milik guru yang sedang login
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

        $kelas = Kelas::with('guru')
            ->where('tapel_id', $tapelId)
            ->where('guru_id', $guru->id_guru)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar kelas wali guru',
            'guru'    => [
                'id_guru' => $guru->id_guru,
                'nama'    => $guru->nama,
            ],
            'data'    => $kelas,
            'filter'  => [
                'tapel_id'    => $tapelId,
                'tapel_aktif' => $tapelAktif,
            ],
        ]);
    }

    /**
     * [GURU] Ambil data absensi per tanggal (untuk form kelola absensi)
     * GET /api/absensi/guru/kelas/{id}/{bulan}/{tanggal}
     */
    public function kelolaGuru($id, $bulan, $tanggal)
    {
        $kelas = Kelas::with(['guru', 'tahunPelajaran', 'siswa'])->findOrFail($id);

        $bulanMap = [
            'januari'  => 1,  'februari' => 2,  'maret'    => 3,
            'april'    => 4,  'mei'       => 5,  'juni'     => 6,
            'juli'     => 7,  'agustus'   => 8,  'september'=> 9,
            'oktober'  => 10, 'november'  => 11, 'desember' => 12,
        ];

        $bulanAngka     = $bulanMap[strtolower($bulan)] ?? now()->month;
        $tanggalAbsensi = now()->setMonth($bulanAngka)->setDay((int) $tanggal);
        $siswaIds       = $kelas->siswa->pluck('id_siswa');

        $absensi = Absensi::whereIn('siswa_id', $siswaIds)
            ->whereDate('tanggal', $tanggalAbsensi->format('Y-m-d'))
            ->get()
            ->keyBy('siswa_id');

        $daftarSiswa = $kelas->siswa->map(function ($siswa) use ($absensi) {
            $a = $absensi->get($siswa->id_siswa);
            return [
                'id_siswa'        => $siswa->id_siswa,
                'nis'             => $siswa->nis,
                'nama'            => $siswa->nama,
                'jenis_kelamin'   => $siswa->jenis_kelamin,
                'status_kehadiran'=> $a?->status_kehadiran ?? null,
                'keterangan'      => $a?->keterangan ?? null,
            ];
        });

        return response()->json([
            'status'  => 'success',
            'message' => "Data absensi tanggal {$tanggal} {$bulan}",
            'data'    => [
                'kelas'          => [
                    'id_kelas'   => $kelas->id_kelas,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tingkat'    => $kelas->tingkat,
                ],
                'tanggal'        => $tanggalAbsensi->format('Y-m-d'),
                'bulan'          => $bulan,
                'bulan_angka'    => $bulanAngka,
                'siswa'          => $daftarSiswa,
            ],
        ]);
    }

    /**
     * [GURU] Simpan / update absensi per tanggal
     * POST /api/absensi/guru/kelas/{id}/{bulan}/{tanggal}/simpan
     *
     * Body (JSON):
     * {
     *   "status": { "1": "H", "2": "S", "3": "I" },
     *   "keterangan": { "2": "Demam", "3": "Keperluan keluarga" }
     * }
     */
    public function simpanGuru(Request $request, $id, $bulan, $tanggal)
    {
        $validator = Validator::make($request->all(), [
            'status'      => 'required|array',
            'status.*'    => 'required|in:H,S,I,A',
            'keterangan'  => 'nullable|array',
            'keterangan.*'=> 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $bulanMap = [
            'januari'  => 1,  'februari' => 2,  'maret'    => 3,
            'april'    => 4,  'mei'       => 5,  'juni'     => 6,
            'juli'     => 7,  'agustus'   => 8,  'september'=> 9,
            'oktober'  => 10, 'november'  => 11, 'desember' => 12,
        ];

        $bulanAngka     = $bulanMap[strtolower($bulan)] ?? now()->month;
        $tanggalAbsensi = now()->setMonth($bulanAngka)->setDay((int) $tanggal);

        $saved = [];
        foreach ($request->status as $siswaId => $status) {
            $record = Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal'  => $tanggalAbsensi->format('Y-m-d'),
                ],
                [
                    'hari'             => $tanggalAbsensi->translatedFormat('l'),
                    'status_kehadiran' => $status,
                    'keterangan'       => $request->keterangan[$siswaId] ?? null,
                ]
            );
            $saved[] = $record;
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Absensi berhasil disimpan.',
            'tanggal' => $tanggalAbsensi->format('Y-m-d'),
            'total'   => count($saved),
            'data'    => $saved,
        ]);
    }

    /**
     * [GURU] Detail absensi per kelas per bulan (view guru)
     * GET /api/absensi/guru/kelas/{id}/{bulan}/detail
     */
    public function detailGuru($id, $bulan)
    {
        $kelas = Kelas::with(['guru', 'tahunPelajaran', 'siswa'])->findOrFail($id);

        $bulanMap = [
            'januari'  => 1,  'februari' => 2,  'maret'    => 3,
            'april'    => 4,  'mei'       => 5,  'juni'     => 6,
            'juli'     => 7,  'agustus'   => 8,  'september'=> 9,
            'oktober'  => 10, 'november'  => 11, 'desember' => 12,
        ];

        $bulanAngka = $bulanMap[strtolower($bulan)] ?? now()->month;
        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulanAngka, now()->year);
        $siswaIds   = $kelas->siswa->pluck('id_siswa');

        $absensiRaw = Absensi::whereIn('siswa_id', $siswaIds)
            ->whereMonth('tanggal', $bulanAngka)
            ->get();

        $absensiBulan = [];
        foreach ($absensiRaw as $item) {
            $hari = Carbon::parse($item->tanggal)->day;
            $absensiBulan[$item->siswa_id][$hari] = $item->status_kehadiran;
        }

        $rekap = $kelas->siswa->map(function ($siswa) use ($absensiRaw, $absensiBulan) {
            $absensiSiswa = $absensiRaw->where('siswa_id', $siswa->id_siswa);
            return [
                'id_siswa'        => $siswa->id_siswa,
                'nis'             => $siswa->nis,
                'nama'            => $siswa->nama,
                'jenis_kelamin'   => $siswa->jenis_kelamin,
                'absensi_per_hari'=> $absensiBulan[$siswa->id_siswa] ?? [],
                'rekap'           => [
                    'hadir' => $absensiSiswa->where('status_kehadiran', 'H')->count(),
                    'sakit' => $absensiSiswa->where('status_kehadiran', 'S')->count(),
                    'izin'  => $absensiSiswa->where('status_kehadiran', 'I')->count(),
                    'alpha' => $absensiSiswa->where('status_kehadiran', 'A')->count(),
                ],
            ];
        });

        return response()->json([
            'status'  => 'success',
            'message' => "Detail absensi kelas {$kelas->nama_kelas} bulan {$bulan}",
            'data'    => [
                'kelas'       => [
                    'id_kelas'        => $kelas->id_kelas,
                    'nama_kelas'      => $kelas->nama_kelas,
                    'tingkat'         => $kelas->tingkat,
                    'guru'            => $kelas->guru,
                    'tahun_pelajaran' => $kelas->tahunPelajaran,
                ],
                'bulan'       => $bulan,
                'bulan_angka' => $bulanAngka,
                'jumlah_hari' => $jumlahHari,
                'siswa'       => $rekap,
            ],
        ]);
    }
}
