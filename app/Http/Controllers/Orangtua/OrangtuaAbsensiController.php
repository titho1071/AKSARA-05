<?php

namespace App\Http\Controllers\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrangtuaAbsensiController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $user = Auth::user();

        $orangTua = OrangTua::where('user_id', $user->id)
            ->firstOrFail();

        $siswaList = $orangTua->siswa()
            ->with('kelas')
            ->get();

        if ($siswaList->isEmpty()) {
            return view('Dashboard_Orangtua.Absensi.absensi-orangtua', [
                'siswa' => collect(),
                'activeSiswa' => null,
                'summary' => [
                    'hadir' => 0,
                    'sakit' => 0,
                    'izin' => 0,
                    'alpha' => 0,
                    'total' => 0,
                    'persen' => 0,
                ],
                'records' => collect(),
                'bulan' => now()->month,
                'tahun' => now()->year,
                'statusFilter' => null,
            ]);
        }

        $siswaId = $request->siswa_id
            ?? $siswaList->first()->id_siswa;

        $activeSiswa = $siswaList
            ->firstWhere('id_siswa', $siswaId);

        if (!$activeSiswa) {
            $activeSiswa = $siswaList->first();
            $siswaId = $activeSiswa->id_siswa;
        }

        // Format data siswa untuk Blade
        $siswa = $siswaList->map(function ($item) use ($siswaId) {

            $namaParts = explode(' ', $item->nama);

            $initials = collect($namaParts)
                ->take(2)
                ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                ->implode('');

            return [
                'id' => $item->id_siswa,
                'name' => $item->nama,
                'kelas' => $item->kelas->nama_kelas ?? '-',
                'initials' => $initials,
                'active' => $item->id_siswa == $siswaId,
            ];
        });

        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $absensi = Absensi::where('siswa_id', $siswaId)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal')
            ->get();

        $summary = [
            'hadir' => $absensi->where('status_kehadiran', 'H')->count(),
            'sakit' => $absensi->where('status_kehadiran', 'S')->count(),
            'izin'  => $absensi->where('status_kehadiran', 'I')->count(),
            'alpha' => $absensi->where('status_kehadiran', 'A')->count(),
            'total' => $absensi->count(),
        ];

        $summary['persen'] = $summary['total'] > 0
            ? round(($summary['hadir'] / $summary['total']) * 100)
            : 0;

        $statusFilter = $request->status;

        $statusMapFilter = [
            'Hadir' => 'H',
            'Sakit' => 'S',
            'Izin'  => 'I',
            'Alpha' => 'A',
        ];

        if ($statusFilter && $statusFilter !== 'Semua') {
            $absensi = $absensi->where(
                'status_kehadiran',
                $statusMapFilter[$statusFilter] ?? $statusFilter
            );
        }

        $records = $absensi
            ->values()
            ->map(function ($item, $index) {

                $statusMap = [
                    'H' => 'Hadir',
                    'S' => 'Sakit',
                    'I' => 'Izin',
                    'A' => 'Alpha',
                ];

                return [
                    'no' => $index + 1,
                    'tanggal' => Carbon::parse($item->tanggal)
                        ->translatedFormat('d F Y'),

                    'hari' => Carbon::parse($item->tanggal)
                        ->translatedFormat('l'),

                    'status' => $statusMap[$item->status_kehadiran]
                        ?? $item->status_kehadiran,

                    'keterangan' => $item->keterangan,
                ];
            });

        return view(
            'Dashboard_Orangtua.Absensi.absensi-orangtua',
            compact(
                'siswa',
                'activeSiswa',
                'summary',
                'records',
                'bulan',
                'tahun',
                'statusFilter'
            )
        );
    }
}