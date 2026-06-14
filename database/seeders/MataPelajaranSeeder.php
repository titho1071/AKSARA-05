<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active tahun pelajaran
        $tapel = DB::table('tahun_pelajaran')
            ->where('is_active', 1)
            ->first();

        if (!$tapel) {
            $this->command->warn('Tidak ada tahun pelajaran aktif. Silakan buat tahun pelajaran terlebih dahulu.');
            return;
        }

        $mataPelajaran = [
            ['nama_mapel' => 'Matematika', 'id_tapel' => $tapel->id_tapel],
            ['nama_mapel' => 'Bahasa Indonesia', 'id_tapel' => $tapel->id_tapel],
            ['nama_mapel' => 'Bahasa Inggris', 'id_tapel' => $tapel->id_tapel],
            ['nama_mapel' => 'IPA', 'id_tapel' => $tapel->id_tapel],
            ['nama_mapel' => 'IPS', 'id_tapel' => $tapel->id_tapel],
            ['nama_mapel' => 'Pendidikan Agama', 'id_tapel' => $tapel->id_tapel],
            ['nama_mapel' => 'Pendidikan Kewarganegaraan', 'id_tapel' => $tapel->id_tapel],
            ['nama_mapel' => 'Seni Budaya', 'id_tapel' => $tapel->id_tapel],
            ['nama_mapel' => 'Pendidikan Jasmani', 'id_tapel' => $tapel->id_tapel],
            ['nama_mapel' => 'Prakarya', 'id_tapel' => $tapel->id_tapel],
        ];

        $timestamp = Carbon::now();
        foreach ($mataPelajaran as &$mapel) {
            $mapel['created_at'] = $timestamp;
            $mapel['updated_at'] = $timestamp;
        }

        DB::table('mata_pelajaran')->insert($mataPelajaran);

        $this->command->info('Mata pelajaran berhasil ditambahkan!');
    }
}
