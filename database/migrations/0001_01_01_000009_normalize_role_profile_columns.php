<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->fixAdminTable();
        $this->fixGuruTable();
        $this->fixOrangTuaTable();
    }

    private function fixAdminTable(): void
    {
        if (!Schema::hasTable('admin')) {
            return;
        }

        if (Schema::hasColumn('admin', 'gender') && !Schema::hasColumn('admin', 'jenis_kelamin')) {
            DB::statement('ALTER TABLE admin CHANGE COLUMN gender jenis_kelamin VARCHAR(20) NULL');
        }

        if (Schema::hasColumn('admin', 'telepon') && !Schema::hasColumn('admin', 'no_hp')) {
            DB::statement('ALTER TABLE admin CHANGE COLUMN telepon no_hp VARCHAR(20) NULL');
        }

        if (Schema::hasColumn('admin', 'address') && !Schema::hasColumn('admin', 'alamat')) {
            DB::statement('ALTER TABLE admin CHANGE COLUMN address alamat TEXT NULL');
        }

        Schema::table('admin', function (Blueprint $table) {
            if (!Schema::hasColumn('admin', 'nip')) {
                $table->string('nip', 50)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('admin', 'nuptk')) {
                $table->string('nuptk', 50)->nullable()->after('nip');
            }
            if (!Schema::hasColumn('admin', 'jenis_kelamin')) {
                $table->string('jenis_kelamin', 20)->nullable()->after('nuptk');
            }
            if (!Schema::hasColumn('admin', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('admin', 'alamat')) {
                $table->text('alamat')->nullable()->after('no_hp');
            }
        });

        Schema::table('admin', function (Blueprint $table) {
            if (Schema::hasColumn('admin', 'created_at') || Schema::hasColumn('admin', 'updated_at')) {
                $table->dropColumn(['created_at', 'updated_at']);
            }
        });
    }

    private function fixGuruTable(): void
    {
        if (!Schema::hasTable('guru')) {
            return;
        }

        if (Schema::hasColumn('guru', 'gender') && !Schema::hasColumn('guru', 'jenis_kelamin')) {
            DB::statement('ALTER TABLE guru CHANGE COLUMN gender jenis_kelamin VARCHAR(20) NULL');
        }

        if (Schema::hasColumn('guru', 'telepon') && !Schema::hasColumn('guru', 'no_hp')) {
            DB::statement('ALTER TABLE guru CHANGE COLUMN telepon no_hp VARCHAR(20) NULL');
        }

        if (Schema::hasColumn('guru', 'address') && !Schema::hasColumn('guru', 'alamat')) {
            DB::statement('ALTER TABLE guru CHANGE COLUMN address alamat TEXT NULL');
        }

        Schema::table('guru', function (Blueprint $table) {
            if (!Schema::hasColumn('guru', 'nip')) {
                $table->string('nip', 50)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('guru', 'nuptk')) {
                $table->string('nuptk', 50)->nullable()->after('nip');
            }
            if (!Schema::hasColumn('guru', 'jenis_kelamin')) {
                $table->string('jenis_kelamin', 20)->nullable()->after('nuptk');
            }
            if (!Schema::hasColumn('guru', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('guru', 'alamat')) {
                $table->text('alamat')->nullable()->after('no_hp');
            }
        });

        Schema::table('guru', function (Blueprint $table) {
            if (Schema::hasColumn('guru', 'created_at') || Schema::hasColumn('guru', 'updated_at')) {
                $table->dropColumn(['created_at', 'updated_at']);
            }
        });
    }

    private function fixOrangTuaTable(): void
    {
        if (!Schema::hasTable('orang_tua')) {
            return;
        }

        if (Schema::hasColumn('orang_tua', 'gender') && !Schema::hasColumn('orang_tua', 'jenis_kelamin')) {
            DB::statement('ALTER TABLE orang_tua CHANGE COLUMN gender jenis_kelamin VARCHAR(20) NULL');
        }

        if (Schema::hasColumn('orang_tua', 'telepon') && !Schema::hasColumn('orang_tua', 'no_hp')) {
            DB::statement('ALTER TABLE orang_tua CHANGE COLUMN telepon no_hp VARCHAR(20) NULL');
        }

        if (Schema::hasColumn('orang_tua', 'address') && !Schema::hasColumn('orang_tua', 'alamat')) {
            DB::statement('ALTER TABLE orang_tua CHANGE COLUMN address alamat TEXT NULL');
        }

        Schema::table('orang_tua', function (Blueprint $table) {
            if (!Schema::hasColumn('orang_tua', 'nik')) {
                $table->string('nik', 50)->nullable()->unique()->after('nama');
            }
            if (!Schema::hasColumn('orang_tua', 'jenis_kelamin')) {
                $table->string('jenis_kelamin', 20)->nullable()->after('nik');
            }
            if (!Schema::hasColumn('orang_tua', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('orang_tua', 'alamat')) {
                $table->text('alamat')->nullable()->after('no_hp');
            }
        });

        Schema::table('orang_tua', function (Blueprint $table) {
            if (Schema::hasColumn('orang_tua', 'created_at') || Schema::hasColumn('orang_tua', 'updated_at')) {
                $table->dropColumn(['created_at', 'updated_at']);
            }
        });
    }

    public function down(): void
    {
        // Keep schema changes as irreversible because this migration normalizes role profile tables.
    }
};