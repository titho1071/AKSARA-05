<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class GuruController extends Controller
{
    private function getGuruRoleId(): int
    {
        if (!Schema::hasTable('roles')) {
            abort(500, 'Tabel roles tidak tersedia.');
        }

        return DB::table('roles')->where('nama_role', 'guru')->value('id_role')
            ?: abort(500, 'Role guru tidak ditemukan.');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $guruRoleId = $this->getGuruRoleId();

        $query = DB::table('users')
            ->select(
                'users.id',
                'users.username',
                'users.email',
                'guru.id_guru',
                'guru.nama',
                'guru.nip',
                'guru.nuptk',
                'guru.jenis_kelamin as gender',
                'guru.no_hp as phone',
                'guru.alamat as address'
            )
            ->join('guru', 'guru.user_id', '=', 'users.id')
            ->where('users.role_id', $guruRoleId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('guru.nama', 'like', "%{$search}%")
                    ->orWhere('users.username', 'like', "%{$search}%")
                    ->orWhere('guru.nip', 'like', "%{$search}%")
                    ->orWhere('guru.nuptk', 'like', "%{$search}%");
            });
        }

        $gurus = $query->orderBy('guru.nama')->get();

        return view('Dashboard_Admin.biodata-guru', compact('gurus', 'search'));
    }

    public function create()
    {
        return view('Dashboard_Admin.biodata-guru-create');
    }

    public function store(Request $request)
    {
        $guruRoleId = $this->getGuruRoleId();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nip' => ['nullable', 'string', 'max:50'],
            'nuptk' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Simpan ke tabel users
        $user = User::create([
            'username' => $validated['username'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role_id' => $guruRoleId,
        ]);

        // Simpan ke tabel guru
        DB::table('guru')->insert([
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'nip' => $validated['nip'] ?? null,
            'nuptk' => $validated['nuptk'] ?? null,
            'jenis_kelamin' => $validated['gender'] ?? null,
            'no_hp' => $validated['phone'] ?? null,
            'alamat' => $validated['address'] ?? null,
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $guruRoleId = $this->getGuruRoleId();

        if ($user->role_id !== $guruRoleId) {
            abort(404);
        }

        $profil = DB::table('guru')->where('user_id', $user->id)->first();

        return view('Dashboard_Admin.biodata-guru-edit', compact('user', 'profil'));
    }

    public function update(Request $request, User $user)
    {
        $guruRoleId = $this->getGuruRoleId();

        if ($user->role_id !== $guruRoleId) {
            abort(404);
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'username' => ['required', 'string', 'max:100', 'unique:users,username,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'nip' => ['nullable', 'string', 'max:50'],
            'nuptk' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $userData = [
            'username' => $validated['username'],
            'email' => strtolower($validated['email']),
        ];
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $user->update($userData);

        DB::table('guru')->where('user_id', $user->id)->update([
            'nama' => $validated['nama'],
            'nip' => $validated['nip'] ?? null,
            'nuptk' => $validated['nuptk'] ?? null,
            'jenis_kelamin' => $validated['gender'] ?? null,
            'no_hp' => $validated['phone'] ?? null,
            'alamat' => $validated['address'] ?? null,
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $guruRoleId = $this->getGuruRoleId();

        if ($user->role_id !== $guruRoleId) {
            abort(404);
        }

        DB::table('guru')->where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}