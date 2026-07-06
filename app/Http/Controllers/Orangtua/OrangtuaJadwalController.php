<?php

namespace App\Http\Controllers\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $emptyStats = ['total_jp' => 0, 'total_mapel' => 0, 'hari_aktif' => 0, 'total_kegiatan' => 0];
        $emptyDays  = array_fill_keys(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'], []);

        if ($siswaList->isEmpty()) {
            return view('Dashboard_Orangtua.Jadwal.jadwal-orangtua', [
                'siswa'       => collect(),
                'activeSiswa' => null,
                'jadwal'      => $emptyDays,
                'stats'       => $emptyStats,
                'tapelError'  => false,
            ]);
        }

        $siswaId     = $request->query('siswa_id', $siswaList->first()->id_siswa);
        $activeSiswa = $siswaList->firstWhere('id_siswa', $siswaId) ?? $siswaList->first();
        $kelasId     = $activeSiswa->kelas->id_kelas;

        // Ambil tahun pelajaran aktif — jadwal mengikuti tapel yang sedang aktif
        $tapel = TahunPelajaran::where('is_active', 1)->first();

        if (!$tapel) {
            return view('Dashboard_Orangtua.Jadwal.jadwal-orangtua', [
                'siswa'       => $siswaList,
                'activeSiswa' => $activeSiswa,
                'jadwal'      => $emptyDays,
                'stats'       => $emptyStats,
                'tapelError'  => true,
            ]);
        }

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $jadwalRaw = JadwalPelajaran::with(['jamPelajaran', 'mataPelajaran', 'guru'])
            ->where('kelas_id', $kelasId)
            ->where('id_tapel', $tapel->id_tapel)
            ->whereIn('hari', $days)
            ->orderBy('hari')
            ->get();

        $colors        = ['blue', 'green', 'orange', 'purple', 'red', 'amber', 'teal', 'yellow', 'pink'];
        $mapelColorMap = [];
        $colorIndex    = 0;

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

                // Slot kegiatan (dari field teks bebas nama_kegiatan)
                if (!empty($item->nama_kegiatan)) {
                    $slotList[] = [
                        'type'          => 'kegiatan',
                        'nama_kegiatan' => $item->nama_kegiatan,
                        'jam'           => substr($jam->jam_mulai, 0, 5) . ' - ' . substr($jam->jam_selesai, 0, 5),
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

        // Hitung statistik dari $jadwal yang SUDAH dibangun (bukan $jadwalRaw mentah),
        // supaya baris yang jam_id-nya kebetulan jatuh di slot ISTIRAHAT
        // (data nyasar/sampah) otomatis tidak ikut terhitung — sama seperti
        // yang terjadi di grid tampilan.
        $semuaSlotTerisi = collect($jadwal)->flatten(1)
            ->filter(fn($item) => in_array($item['type'], ['pelajaran', 'kegiatan']));

        $stats = [
            'total_jp'       => $semuaSlotTerisi->count(),

            'total_mapel'    => $semuaSlotTerisi
                                    ->where('type', 'pelajaran')
                                    ->pluck('mapel')
                                    ->filter()
                                    ->unique()
                                    ->count(),

            'hari_aktif'     => collect($jadwal)
                                    ->filter(fn($items) => collect($items)->contains(
                                        fn($item) => in_array($item['type'], ['pelajaran', 'kegiatan'])
                                    ))
                                    ->count(),

            'total_kegiatan' => $semuaSlotTerisi->where('type', 'kegiatan')->count(),
        ];

        return view('Dashboard_Orangtua.Jadwal.jadwal-orangtua', [
            'siswa'       => $siswaList,
            'activeSiswa' => $activeSiswa,
            'jadwal'      => $jadwal,
            'stats'       => $stats,
            'tapelError'  => false,
        ]);
    }
}