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
        if (Schema::hasTable('admin')) {
            Schema::table('admin', function (Blueprint $table) {
                if (!Schema::hasColumn('admin', 'foto_profil')) {
                    $table->string('foto_profil')->nullable()->after('user_id');
                }
            });
        }

        if (Schema::hasTable('guru')) {
            Schema::table('guru', function (Blueprint $table) {
                if (!Schema::hasColumn('guru', 'foto_profil')) {
                    $table->string('foto_profil')->nullable()->after('user_id');
                }
            });
        }

        if (Schema::hasTable('orang_tua')) {
            Schema::table('orang_tua', function (Blueprint $table) {
                if (!Schema::hasColumn('orang_tua', 'foto_profil')) {
                    $table->string('foto_profil')->nullable()->after('user_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('admin')) {
            Schema::table('admin', function (Blueprint $table) {
                $table->dropColumn('foto_profil');
            });
        }

        if (Schema::hasTable('guru')) {
            Schema::table('guru', function (Blueprint $table) {
                $table->dropColumn('foto_profil');
            });
        }

        if (Schema::hasTable('orang_tua')) {
            Schema::table('orang_tua', function (Blueprint $table) {
                $table->dropColumn('foto_profil');
            });
        }
    }
};
