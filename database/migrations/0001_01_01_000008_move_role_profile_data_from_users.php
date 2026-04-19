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

        if ($adminRoleId && Schema::hasTable('admin') && Schema::hasColumn('users', 'nip')) {
            DB::statement(
                'INSERT IGNORE INTO admin (user_id, nama, nip, nuptk, jenis_kelamin, no_hp, alamat)
                 SELECT u.id, u.name, u.nip, u.nuptk, u.gender, u.phone, u.address
                 FROM users u
                 LEFT JOIN admin a ON a.user_id = u.id
                 WHERE u.role_id = ? AND a.user_id IS NULL',
                [$adminRoleId]
            );
        }

        if ($guruRoleId && Schema::hasTable('guru') && Schema::hasColumn('users', 'nip')) {
            DB::statement(
                'INSERT IGNORE INTO guru (user_id, nama, nip, nuptk, jenis_kelamin, no_hp, alamat)
                 SELECT u.id, u.name, u.nip, u.nuptk, u.gender, u.phone, u.address
                 FROM users u
                 LEFT JOIN guru g ON g.user_id = u.id
                 WHERE u.role_id = ? AND g.user_id IS NULL',
                [$guruRoleId]
            );
        }

        if ($ortuRoleId && Schema::hasTable('orang_tua') && Schema::hasColumn('users', 'nip')) {
            DB::statement(
                'INSERT IGNORE INTO orang_tua (user_id, nama, nik, jenis_kelamin, no_hp, alamat)
                 SELECT u.id, u.name, u.nip, u.gender, u.phone, u.address
                 FROM users u
                 LEFT JOIN orang_tua o ON o.user_id = u.id
                 WHERE u.role_id = ? AND o.user_id IS NULL',
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