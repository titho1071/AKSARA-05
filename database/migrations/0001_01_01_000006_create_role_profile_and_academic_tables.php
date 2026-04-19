<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admin')) {
            Schema::create('admin', function (Blueprint $table) {
                $table->id('id_admin');
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
                $table->string('nama', 100);
                $table->string('nip', 50)->nullable();
                $table->string('nuptk', 50)->nullable();
                $table->string('jenis_kelamin', 20)->nullable();
                $table->string('no_hp', 20)->nullable();
                $table->text('alamat')->nullable();
            });
        }

        if (!Schema::hasTable('guru')) {
            Schema::create('guru', function (Blueprint $table) {
                $table->id('id_guru');
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
                $table->string('nama', 100);
                $table->string('nip', 50)->nullable();
                $table->string('nuptk', 50)->nullable();
                $table->string('jenis_kelamin', 20)->nullable();
                $table->string('no_hp', 20)->nullable();
                $table->text('alamat')->nullable();
            });
        }

        if (!Schema::hasTable('orang_tua')) {
            Schema::create('orang_tua', function (Blueprint $table) {
                $table->id('id_orang_tua');
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
                $table->string('nama', 100);
                $table->string('nik', 50)->nullable()->unique();
                $table->string('jenis_kelamin', 20)->nullable();
                $table->string('no_hp', 20)->nullable();
                $table->text('alamat')->nullable();
            });
        }

        if (!Schema::hasTable('kelas')) {
            Schema::create('kelas', function (Blueprint $table) {
                $table->id('id_kelas');
                $table->string('nama_kelas', 100);
                $table->integer('tingkat');
                $table->string('tahun_pelajaran', 9);
                $table->foreignId('wali_kelas_id')->nullable()->constrained('guru', 'id_guru')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('siswa')) {
            Schema::create('siswa', function (Blueprint $table) {
                $table->id('id_siswa');
                $table->string('nama', 100);
                $table->string('nis', 20)->unique();
                $table->string('nisn', 20)->unique();
                $table->enum('jenis_kelamin', ['L', 'P']);
                $table->date('tanggal_lahir');
                $table->text('alamat')->nullable();
                $table->foreignId('kelas_id')->constrained('kelas', 'id_kelas')->cascadeOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('orang_tua_siswa')) {
            Schema::create('orang_tua_siswa', function (Blueprint $table) {
                $table->id();
                $table->foreignId('orang_tua_id')->constrained('orang_tua', 'id_orang_tua')->cascadeOnDelete();
                $table->foreignId('siswa_id')->constrained('siswa', 'id_siswa')->cascadeOnDelete();
                $table->enum('hubungan', ['ayah', 'ibu', 'wali']);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('orang_tua_siswa');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('orang_tua');
        Schema::dropIfExists('guru');
        Schema::dropIfExists('admin');
    }
};