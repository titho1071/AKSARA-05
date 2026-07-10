<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrangTuaImport implements ToCollection, WithHeadingRow
{
    private function getOrangTuaRoleId(): int
    {
        return DB::table('roles')->where('nama_role', 'orang_tua')->value('id_role')
            ?: abort(500, 'Role orang tua tidak ditemukan.');
    }

    public function collection(Collection $rows)
    {
        $roleId = $this->getOrangTuaRoleId();

        foreach ($rows as $row) {
            if (empty($row['nama']) || empty($row['email']) || empty($row['username'])) {
                continue;
            }

            $email = strtolower(trim((string)$row['email']));
            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                // allow reuse if existing user is a guru
                $existingRole = DB::table('roles')->where('id_role', $existingUser->role_id)->value('nama_role');
                if ($existingRole !== 'guru') {
                    // email already used by non-guru (another orang tua/admin) -> skip
                    continue;
                }

                // if user already has an orang_tua profile, skip
                if (DB::table('orang_tua')->where('user_id', $existingUser->id)->exists()) {
                    continue;
                }

                // reuse existing guru user for orang_tua profile
                $user = $existingUser;
            } else {
                // username must be unique globally
                if (User::where('username', $row['username'])->exists()) {
                    continue;
                }

                $user = User::create([
                    'username' => $row['username'],
                    'email' => $email,
                    'password' => Hash::make($row['password'] ?? 'password123'),
                    'role_id' => $roleId,
                ]);
            }

            DB::table('orang_tua')->insert([
                'user_id' => $user->id,
                'nama' => $row['nama'],
                'nik' => $row['nik'] ?? null,
                'jenis_kelamin' => $this->normalizeGender($row['jenis_kelamin'] ?? null),
                'no_hp' => $row['no_hp'] ?? null,
                'alamat' => $row['alamat'] ?? null,
                'status' => $this->normalizeStatus($row['status'] ?? null),
            ]);
        }
    }

    private function normalizeStatus($value): string
    {
        $v = strtolower(trim((string)($value ?? '')));
        if ($v === '') return 'aktif';

        $aktifValues = ['aktif', 'active', 'ya', 'yes'];
        $nonaktifValues = ['tidak aktif', 'tidak_aktif', 'nonaktif', 'non-active', 'no', 'tidak'];

        if (in_array($v, $aktifValues, true)) return 'aktif';
        if (in_array($v, $nonaktifValues, true)) return 'tidak_aktif';

        return 'aktif';
    }

    private function normalizeGender($value): ?string
    {
        $v = strtolower(trim((string)($value ?? '')));
        if ($v === '') return null;

        $male = ['l', 'laki', 'laki-laki', 'laki laki', 'male'];
        $female = ['p', 'perempuan', 'wanita', 'female'];

        if (in_array($v, $male, true)) return 'L';
        if (in_array($v, $female, true)) return 'P';

        return null;
    }
}
