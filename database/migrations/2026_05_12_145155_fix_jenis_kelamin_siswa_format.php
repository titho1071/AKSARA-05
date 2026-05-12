<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert existing data from old format to new format
        DB::table('siswa')
            ->where('jenis_kelamin', 'Laki-laki')
            ->update(['jenis_kelamin' => 'L']);

        DB::table('siswa')
            ->where('jenis_kelamin', 'Perempuan')
            ->update(['jenis_kelamin' => 'P']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data back to old format
        DB::table('siswa')
            ->where('jenis_kelamin', 'L')
            ->update(['jenis_kelamin' => 'Laki-laki']);

        DB::table('siswa')
            ->where('jenis_kelamin', 'P')
            ->update(['jenis_kelamin' => 'Perempuan']);
    }
};
