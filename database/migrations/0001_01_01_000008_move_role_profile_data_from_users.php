<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        $adminRoleId = DB::table('roles')->where('nama_role', 'admin')->value('id_role');
        $guruRoleId = DB::table('roles')->where('nama_role', 'guru')->value('id_role');
        $ortuRoleId = DB::table('roles')->where('nama_role', 'orang_tua')->value('id_role');

        // Check if 'name' column exists, otherwise use 'username'
        $nameColumn = Schema::hasColumn('users', 'name') ? 'u.name' : 'u.username';

        if ($adminRoleId && Schema::hasTable('admin') && Schema::hasColumn('users', 'nip')) {
            // Build dynamic column list based on what exists
            $columns = ['user_id', 'nama', 'nip', 'nuptk', 'jenis_kelamin'];
            $selectCols = ['u.id', $nameColumn, 'u.nip', 'u.nuptk', 'u.gender'];
            
            if (Schema::hasColumn('admin', 'no_hp')) {
                $columns[] = 'no_hp';
                $selectCols[] = 'u.phone';
            }
            if (Schema::hasColumn('admin', 'alamat')) {
                $columns[] = 'alamat';
                $selectCols[] = 'u.address';
            }

            DB::statement(
                "INSERT IGNORE INTO admin (" . implode(',', $columns) . ")
                 SELECT " . implode(',', $selectCols) . "
                 FROM users u
                 LEFT JOIN admin a ON a.user_id = u.id
                 WHERE u.role_id = ? AND a.user_id IS NULL",
                [$adminRoleId]
            );
        }

        if ($guruRoleId && Schema::hasTable('guru') && Schema::hasColumn('users', 'nip')) {
            $columns = ['user_id', 'nama', 'nip', 'nuptk', 'jenis_kelamin'];
            $selectCols = ['u.id', $nameColumn, 'u.nip', 'u.nuptk', 'u.gender'];
            
            if (Schema::hasColumn('guru', 'no_hp')) {
                $columns[] = 'no_hp';
                $selectCols[] = 'u.phone';
            }
            if (Schema::hasColumn('guru', 'alamat')) {
                $columns[] = 'alamat';
                $selectCols[] = 'u.address';
            }

            DB::statement(
                "INSERT IGNORE INTO guru (" . implode(',', $columns) . ")
                 SELECT " . implode(',', $selectCols) . "
                 FROM users u
                 LEFT JOIN guru g ON g.user_id = u.id
                 WHERE u.role_id = ? AND g.user_id IS NULL",
                [$guruRoleId]
            );
        }

        if ($ortuRoleId && Schema::hasTable('orang_tua') && Schema::hasColumn('users', 'nip')) {
            $columns = ['user_id', 'nama', 'nik', 'jenis_kelamin'];
            $selectCols = ['u.id', $nameColumn, 'u.nip', 'u.gender'];
            
            if (Schema::hasColumn('orang_tua', 'no_hp')) {
                $columns[] = 'no_hp';
                $selectCols[] = 'u.phone';
            }
            if (Schema::hasColumn('orang_tua', 'alamat')) {
                $columns[] = 'alamat';
                $selectCols[] = 'u.address';
            }

            DB::statement(
                "INSERT IGNORE INTO orang_tua (" . implode(',', $columns) . ")
                 SELECT " . implode(',', $selectCols) . "
                 FROM users u
                 LEFT JOIN orang_tua o ON o.user_id = u.id
                 WHERE u.role_id = ? AND o.user_id IS NULL",
                [$ortuRoleId]
            );
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip')->nullable()->after('username');
            }
            if (!Schema::hasColumn('users', 'nuptk')) {
                $table->string('nuptk')->nullable()->after('nip');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable()->after('nuptk');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
        });
    }
};