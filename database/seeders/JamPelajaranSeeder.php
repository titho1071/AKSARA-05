<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JamPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jamPelajaran = [
            ['jam_mulai' => '07:00:00', 'jam_selesai' => '07:45:00', 'keterangan' => 'Jam 1'],
            ['jam_mulai' => '07:45:00', 'jam_selesai' => '08:30:00', 'keterangan' => 'Jam 2'],
            ['jam_mulai' => '08:30:00', 'jam_selesai' => '09:15:00', 'keterangan' => 'Jam 3'],
            ['jam_mulai' => '09:15:00', 'jam_selesai' => '09:30:00', 'keterangan' => 'Istirahat 1'],
            ['jam_mulai' => '09:30:00', 'jam_selesai' => '10:15:00', 'keterangan' => 'Jam 4'],
            ['jam_mulai' => '10:15:00', 'jam_selesai' => '11:00:00', 'keterangan' => 'Jam 5'],
            ['jam_mulai' => '11:00:00', 'jam_selesai' => '11:45:00', 'keterangan' => 'Jam 6'],
            ['jam_mulai' => '11:45:00', 'jam_selesai' => '12:30:00', 'keterangan' => 'Istirahat 2'],
            ['jam_mulai' => '12:30:00', 'jam_selesai' => '13:15:00', 'keterangan' => 'Jam 7'],
            ['jam_mulai' => '13:15:00', 'jam_selesai' => '14:00:00', 'keterangan' => 'Jam 8'],
        ];

        $timestamp = Carbon::now();
        foreach ($jamPelajaran as &$jam) {
            $jam['created_at'] = $timestamp;
            $jam['updated_at'] = $timestamp;
        }

        DB::table('jam_pelajaran')->insert($jamPelajaran);

        $this->command->info('Jam pelajaran berhasil ditambahkan!');
    }
}
