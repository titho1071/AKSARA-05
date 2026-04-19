<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orang_tua')) {
            return;
        }

        Schema::table('orang_tua', function (Blueprint $table) {
            if (!Schema::hasColumn('orang_tua', 'nik')) {
                $table->string('nik', 50)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('orang_tua', 'jenis_kelamin')) {
                $table->string('jenis_kelamin', 20)->nullable()->after('nik');
            }
            if (!Schema::hasColumn('orang_tua', 'telepon')) {
                $table->string('telepon', 20)->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('orang_tua', 'alamat')) {
                $table->text('alamat')->nullable()->after('telepon');
            }
        });

        if (Schema::hasColumn('orang_tua', 'no_hp') && Schema::hasColumn('orang_tua', 'telepon')) {
            DB::table('orang_tua')
                ->whereNotNull('no_hp')
                ->update(['telepon' => DB::raw('no_hp')]);

            Schema::table('orang_tua', function (Blueprint $table) {
                $table->dropColumn('no_hp');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('orang_tua')) {
            return;
        }

        Schema::table('orang_tua', function (Blueprint $table) {
            if (Schema::hasColumn('orang_tua', 'telepon')) {
                $table->dropColumn('telepon');
            }
            if (Schema::hasColumn('orang_tua', 'alamat')) {
                $table->dropColumn('alamat');
            }
            if (Schema::hasColumn('orang_tua', 'jenis_kelamin')) {
                $table->dropColumn('jenis_kelamin');
            }
            if (Schema::hasColumn('orang_tua', 'nik')) {
                $table->dropColumn('nik');
            }
        });
    }
};