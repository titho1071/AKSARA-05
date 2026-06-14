<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuruJadwalController extends Controller
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

    public function index()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();

        // Ambil semua jadwal yang di-assign ke guru ini
        $jadwalRaw = JadwalPelajaran::with(['jamPelajaran', 'mataPelajaran', 'kelas', 'kegiatan'])
            ->where('id_guru', $guru->id_guru)
            ->orderBy('hari')
            ->get();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Kelompokkan per hari
        $jadwalPerHari = array_fill_keys($days, []);
        foreach ($jadwalRaw as $item) {
            if (isset($jadwalPerHari[$item->hari])) {
                $jadwalPerHari[$item->hari][] = $item;
            }
        }

        // Urutkan per jam_mulai dalam setiap hari
        foreach ($days as $day) {
            $jadwalPerHari[$day] = collect($jadwalPerHari[$day])
                ->sortBy(fn($j) => $j->jamPelajaran->jam_mulai ?? '99:99')
                ->values()
                ->all();
        }

        // Hari aktif (yang ada jadwalnya)
        $hariAktif = collect($days)->filter(fn($d) => count($jadwalPerHari[$d]) > 0)->values();

        // Hari yang dipilih — default hari ini atau hari pertama yang ada jadwal
        $hariIni       = self::HARI_MAP[now()->format('l')] ?? 'Senin';
        $selectedHari  = request('hari', in_array($hariIni, $hariAktif->all()) ? $hariIni : ($hariAktif->first() ?? 'Senin'));

        // Stats
        $totalJP    = $jadwalRaw->count();
        $totalKelas = $jadwalRaw->pluck('kelas_id')->unique()->count();
        $totalHari  = $hariAktif->count();

        // Inisial nama guru
        $initials = collect(explode(' ', $guru->nama))
            ->map(fn($n) => strtoupper(substr($n, 0, 1)))
            ->take(2)
            ->implode('');

        return view('Dashboard_Guru.Jadwal.jadwal-guru', [
            'guru'          => $guru,
            'initials'      => $initials,
            'jadwalPerHari' => $jadwalPerHari,
            'hariAktif'     => $hariAktif,
            'selectedHari'  => $selectedHari,
            'days'          => $days,
            'stats'         => [
                'total_jp'    => $totalJP,
                'total_kelas' => $totalKelas,
                'total_hari'  => $totalHari,
            ],
        ]);
    }
}