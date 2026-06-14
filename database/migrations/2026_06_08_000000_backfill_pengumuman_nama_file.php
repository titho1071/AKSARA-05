<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pengumuman')
            ->whereNull('nama_file')
            ->whereNotNull('file')
            ->orderBy('id_pengumuman')
            ->each(function ($row) {
                $ext = strtolower(pathinfo($row->file, PATHINFO_EXTENSION));
                $namaFile = $ext ? "Lampiran.{$ext}" : 'Lampiran';

                DB::table('pengumuman')
                    ->where('id_pengumuman', $row->id_pengumuman)
                    ->update(['nama_file' => $namaFile]);
            });
    }

    public function down(): void
    {
        // Tidak dapat mengembalikan nama file asli yang hilang.
    }
};
