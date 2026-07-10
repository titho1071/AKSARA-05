<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToCollection, WithHeadingRow
{
    private function getGuruRoleId(): int
    {
        return DB::table('roles')->where('nama_role', 'guru')->value('id_role')
            ?: abort(500, 'Role guru tidak ditemukan.');
    }

    public function collection(Collection $rows)
    {
        $guruRoleId = $this->getGuruRoleId();

        foreach ($rows as $row) {
            if (empty($row['nama']) || empty($row['email']) || empty($row['username'])) {
                continue;
            }

            if (User::where('email', $row['email'])->orWhere('username', $row['username'])->exists()) {
                continue;
            }

            $user = User::create([
                'username' => $row['username'],
                'email' => strtolower($row['email']),
                'password' => Hash::make($row['password'] ?? 'password123'),
                'role_id' => $guruRoleId,
            ]);

            DB::table('guru')->insert([
                'user_id' => $user->id,
                'nama' => $row['nama'],
                'nip' => $row['nip'] ?? null,
                'nuptk' => $row['nuptk'] ?? null,
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

        // default null if not recognised
        return null;
    }
}
