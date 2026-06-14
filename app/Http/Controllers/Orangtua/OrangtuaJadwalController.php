<?php

namespace App\Http\Controllers\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrangtuaJadwalController extends Controller
{
    private const HARI_MAP = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
    ];

    public function index(Request $request)
    {
        $orangtua  = Auth::user()->orangtua;
        $siswaList = $orangtua->siswa()->with('kelas')->get();

        $emptyStats = ['total_jp' => 0, 'total_mapel' => 0, 'hari_aktif' => 0, 'jp_hari_ini' => 0];

        if ($siswaList->isEmpty()) {
            return view('Dashboard_Orangtua.Jadwal.jadwal-orangtua', [
                'siswa'       => collect(),
                'activeSiswa' => null,
                'jadwal'      => [],
                'stats'       => $emptyStats,
            ]);
        }

        $siswaId     = $request->query('siswa_id', $siswaList->first()->id_siswa);
        $activeSiswa = $siswaList->firstWhere('id_siswa', $siswaId) ?? $siswaList->first();
        $kelasId     = $activeSiswa->kelas->id_kelas;

        // Ambil jadwal + kegiatan via relasi (bukan query terpisah ke tabel kegiatan)
        $jadwalRaw = JadwalPelajaran::with(['jamPelajaran', 'mataPelajaran', 'kegiatan', 'guru'])
            ->where('kelas_id', $kelasId)
            ->orderBy('hari')
            ->get();

        $colors        = ['blue', 'green', 'orange', 'purple', 'red', 'amber', 'teal', 'yellow', 'pink'];
        $mapelColorMap = [];
        $colorIndex    = 0;

        $days     = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jadwal   = array_fill_keys($days, []);
        $semuaJam = JamPelajaran::orderBy('jam_mulai')->get();

        foreach ($days as $day) {
            $slotList = [];

            foreach ($semuaJam as $jam) {
                // Slot istirahat
                if ($jam->keterangan && stripos($jam->keterangan, 'istirahat') !== false) {
                    $slotList[] = [
                        'type' => 'istirahat',
                        'jam'  => substr($jam->jam_mulai, 0, 5) . ' - ' . substr($jam->jam_selesai, 0, 5),
                    ];
                    continue;
                }

                $item = $jadwalRaw->first(
                    fn($j) => $j->hari === $day && $j->jam_id === $jam->id_jam
                );

                if (!$item) continue;

                // Slot kegiatan (jika ada kegiatan yang diassign ke jadwal ini)
                if ($item->kegiatan) {
                    $slotList[] = [
                        'type'      => 'kegiatan',
                        'judul'     => $item->kegiatan->judul,
                        'deskripsi' => $item->kegiatan->deskripsi ?? '',
                        'tanggal'   => Carbon::parse($item->kegiatan->tanggal)->isoFormat('D MMM Y'),
                        'jam'       => substr($jam->jam_mulai, 0, 5) . ' - ' . substr($jam->jam_selesai, 0, 5),
                    ];
                    continue;
                }

                // Slot pelajaran biasa
                $mapelId = $item->id_mapel;
                if (!isset($mapelColorMap[$mapelId])) {
                    $mapelColorMap[$mapelId] = $colors[$colorIndex % count($colors)];
                    $colorIndex++;
                }

                $slotList[] = [
                    'type'  => 'pelajaran',
                    'mapel' => $item->mataPelajaran->nama_mapel ?? '-',
                    'guru'  => $item->guru->nama_guru ?? $item->guru->nama ?? '-', 
                    'jam'   => substr($jam->jam_mulai, 0, 5) . ' - ' . substr($jam->jam_selesai, 0, 5),
                    'jp'    => 1,
                    'color' => $mapelColorMap[$mapelId],
                ];
            }

            $jadwal[$day] = $slotList;
        }

        $hariIni = self::HARI_MAP[now()->format('l')] ?? '';

        $stats = [
            'total_jp'       => $jadwalRaw->count(),
            'total_mapel'    => $jadwalRaw->pluck('id_mapel')->filter()->unique()->count(),
            'hari_aktif'     => $jadwalRaw->pluck('hari')->unique()->count(),
            'total_kegiatan' => $jadwalRaw->filter(fn($j) => !is_null($j->kegiatan_id))->count(),
        ];

        return view('Dashboard_Orangtua.Jadwal.jadwal-orangtua', [
            'siswa'       => $siswaList,
            'activeSiswa' => $activeSiswa,
            'jadwal'      => $jadwal,
            'stats'       => $stats,
        ]);
    }
}