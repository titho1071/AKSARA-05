<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Sekolah',
                'email' => 'admin@sekolah.com',
                'password' => Hash::make('password'),
                'role_id' => 1, // admin
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guru Matematika',
                'email' => 'guru@sekolah.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // guru
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Orang Tua Siswa',
                'email' => 'ortu@sekolah.com',
                'password' => Hash::make('password'),
                'role_id' => 3, // orang_tua
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}