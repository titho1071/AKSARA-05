<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\TahunPelajaran;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;

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

        $tapel = TahunPelajaran::where('is_active', 1)->first();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        if (!$tapel) {
            return view('Dashboard_Guru.Jadwal.jadwal-guru', [
                'guru'          => $guru,
                'initials'      => $this->initials($guru->nama),
                'jadwalPerHari' => array_fill_keys($days, []),
                'hariAktif'     => collect(),
                'selectedHari'  => 'Senin',
                'days'          => $days,
                'tapelError'    => true,
                'stats'         => ['total_jp' => 0, 'total_kelas' => 0, 'total_hari' => 0],
            ]);
        }

        // Ambil jadwal berdasarkan guru + tapel aktif
        $jadwalRaw = JadwalPelajaran::with(['jamPelajaran', 'mataPelajaran', 'kelas'])
            ->where('id_guru', $guru->id_guru)
            ->where('id_tapel', $tapel->id_tapel)
            ->orderBy('hari')
            ->get();

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
        $hariIni      = self::HARI_MAP[now()->format('l')] ?? 'Senin';
        $selectedHari = request('hari', in_array($hariIni, $hariAktif->all()) ? $hariIni : ($hariAktif->first() ?? 'Senin'));

        return view('Dashboard_Guru.Jadwal.jadwal-guru', [
            'guru'          => $guru,
            'initials'      => $this->initials($guru->nama),
            'jadwalPerHari' => $jadwalPerHari,
            'hariAktif'     => $hariAktif,
            'selectedHari'  => $selectedHari,
            'days'          => $days,
            'tapelError'    => false,
            'stats'         => [
                'total_jp'    => $jadwalRaw->count(),
                'total_kelas' => $jadwalRaw->pluck('kelas_id')->unique()->count(),
                'total_hari'  => $hariAktif->count(),
            ],
        ]);
    }

    private function initials(string $nama): string
    {
        return collect(explode(' ', $nama))
            ->map(fn($n) => strtoupper(substr($n, 0, 1)))
            ->take(2)
            ->implode('');
    }
}