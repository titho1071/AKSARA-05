<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Absensi;

class JuneAbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data untuk Juni 2026 (hari sekolah: senin-jumat)
        $dates = [
            '2026-06-01' => ['status' => 'H', 'keterangan' => null], // Senin - Hadir
            '2026-06-02' => ['status' => 'H', 'keterangan' => null], // Selasa - Hadir
            '2026-06-03' => ['status' => 'S', 'keterangan' => 'Demam tinggi'], // Rabu - Sakit
            '2026-06-04' => ['status' => 'H', 'keterangan' => null], // Kamis - Hadir
            '2026-06-05' => ['status' => 'I', 'keterangan' => 'Kunjungan dokter'], // Jumat - Izin
            '2026-06-08' => ['status' => 'H', 'keterangan' => null], // Senin - Hadir
            '2026-06-09' => ['status' => 'A', 'keterangan' => 'Tidak ada keterangan'], // Selasa - Alpha
            '2026-06-10' => ['status' => 'H', 'keterangan' => null], // Rabu - Hadir
            '2026-06-11' => ['status' => 'H', 'keterangan' => null], // Kamis - Hadir
            '2026-06-12' => ['status' => 'H', 'keterangan' => null], // Jumat - Hadir
            '2026-06-15' => ['status' => 'H', 'keterangan' => null], // Senin - Hadir
            '2026-06-16' => ['status' => 'H', 'keterangan' => null], // Selasa - Hadir
            '2026-06-17' => ['status' => 'S', 'keterangan' => 'Pilek'], // Rabu - Sakit
            '2026-06-18' => ['status' => 'H', 'keterangan' => null], // Kamis - Hadir
            '2026-06-19' => ['status' => 'H', 'keterangan' => null], // Jumat - Hadir
        ];

        $siswaId = 4; // ID siswa yang punya orang tua

        foreach ($dates as $tanggal => $data) {
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                ],
                [
                    'status_kehadiran' => $data['status'],
                    'keterangan' => $data['keterangan'],
                ]
            );
        }
    }
}
