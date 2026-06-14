<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->unsignedBigInteger('jam_id')->nullable()->after('hari');
            $table->unsignedBigInteger('kelas_id')->nullable()->after('jam_id');
            
            $table->foreign('jam_id')->references('id_jam')->on('jam_pelajaran')->onDelete('cascade');
            $table->foreign('kelas_id')->references('id_kelas')->on('kelas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->dropForeign(['jam_id']);
            $table->dropForeign(['kelas_id']);
            $table->dropColumn(['jam_id', 'kelas_id']);
        });
    }
};
