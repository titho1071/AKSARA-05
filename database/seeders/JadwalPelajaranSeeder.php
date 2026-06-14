<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all mata pelajaran
        $mataPelajaran = DB::table('mata_pelajaran')->get();

        if ($mataPelajaran->isEmpty()) {
            $this->command->warn('Tidak ada mata pelajaran. Silakan jalankan MataPelajaranSeeder terlebih dahulu.');
            return;
        }

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwalPelajaran = [];
        $timestamp = Carbon::now();

        // Buat jadwal untuk setiap hari
        $index = 0;
        foreach ($hari as $namaHari) {
            // Setiap hari ada 3-4 mata pelajaran
            $jumlahMapel = $namaHari === 'Sabtu' ? 3 : 4;
            
            for ($i = 0; $i < $jumlahMapel; $i++) {
                if (isset($mataPelajaran[$index % $mataPelajaran->count()])) {
                    $jadwalPelajaran[] = [
                        'hari' => $namaHari,
                        'id_mapel' => $mataPelajaran[$index % $mataPelajaran->count()]->id_mapel,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];
                    $index++;
                }
            }
        }

        DB::table('jadwal_pelajaran')->insert($jadwalPelajaran);

        $this->command->info('Jadwal pelajaran berhasil ditambahkan!');
    }
}
