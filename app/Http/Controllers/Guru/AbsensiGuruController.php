<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiGuruController extends Controller
{
    public function index(Request $request)
    {
        $tahunPelajaran = TahunPelajaran::orderByDesc('created_at')->get();

        $tapelAktif = TahunPelajaran::where('is_active', 1)->first();

        $tapelId = $request->tapel
            ?? $tapelAktif?->id_tapel
            ?? $tahunPelajaran->first()?->id_tapel;

        // Ambil data guru yang sedang login
        $guru = Guru::where('user_id', Auth::id())->first();

        // Filter hanya kelas yang diwali oleh guru yang login
        $kelas = Kelas::with('guru')
            ->where('tapel_id', $tapelId)
            ->where('guru_id', $guru?->id_guru)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate(10)
            ->withQueryString();

        return view('Dashboard_Guru.Absensi.absensi', compact(
            'kelas',
            'tahunPelajaran',
            'tapelId',
            'tapelAktif'
        ));
    }

    public function kelola($id, $bulan, $tanggal)
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

        $tanggalAbsensi = now()
            ->setMonth($bulanAngka)
            ->setDay((int)$tanggal);

        $siswaIds = $kelas->siswa->pluck('id_siswa');

        $absensi = Absensi::whereIn('siswa_id', $siswaIds)
            ->whereDate(
                'tanggal',
                $tanggalAbsensi->format('Y-m-d')
            )
            ->get()
            ->keyBy('siswa_id');

        return view(
            'Dashboard_Guru.Absensi.kelola-absensi',
            compact(
                'kelas',
                'bulan',
                'bulanAngka',
                'tanggal',
                'tanggalAbsensi',
                'absensi'
            )
        );
    }

    public function simpan(Request $request, $id, $bulan, $tanggal)
    {
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

        $tanggalAbsensi = now()
            ->setMonth($bulanAngka)
            ->setDay((int) $tanggal);

        foreach ($request->status as $siswaId => $status) {

            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggalAbsensi->format('Y-m-d'),
                ],
                [
                    'hari' => $tanggalAbsensi->translatedFormat('l'),
                    'status_kehadiran' => $status,
                    'keterangan' => $request->keterangan[$siswaId] ?? null,
                ]
            );
        }

        return redirect()
            ->route('guru.absensi.detail', [
                'id' => $id,
                'bulan' => $bulan,
            ])
            ->with('success', 'Absensi berhasil disimpan.');
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
            'Dashboard_Guru.Absensi.detail-absensi',
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