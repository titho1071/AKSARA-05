<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;

try {
    DB::statement('ALTER TABLE kegiatan ADD COLUMN kelas_id VARCHAR(255) NULL AFTER user_id');
    echo "✓ Kolom kelas_id berhasil ditambahkan ke tabel kegiatan!\n";
} catch (\Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "✓ Kolom kelas_id sudah ada di tabel kegiatan.\n";
    } else {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}
