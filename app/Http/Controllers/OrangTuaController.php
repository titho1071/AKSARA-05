<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class OrangTuaController extends Controller
{
    private function getOrangTuaRoleId(): int
    {
        if (!Schema::hasTable('roles')) {
            abort(500, 'Tabel roles tidak tersedia.');
        }

        return DB::table('roles')->where('nama_role', 'orang_tua')->value('id_role')
            ?: abort(500, 'Role orang tua tidak ditemukan.');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $orangTuaRoleId = $this->getOrangTuaRoleId();

        $query = DB::table('users')
            ->select(
                'users.id',
                'users.username',
                'users.email',
                'orang_tua.id_orang_tua',
                'orang_tua.nama',
                'orang_tua.nik',
                'orang_tua.jenis_kelamin as gender',
                'orang_tua.no_hp as phone',
                'orang_tua.alamat as address'
            )
            ->join('orang_tua', 'orang_tua.user_id', '=', 'users.id')
            ->where('users.role_id', $orangTuaRoleId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('orang_tua.nama', 'like', "%{$search}%")
                    ->orWhere('users.username', 'like', "%{$search}%")
                    ->orWhere('orang_tua.nik', 'like', "%{$search}%")
                    ->orWhere('orang_tua.no_hp', 'like', "%{$search}%");
            });
        }

        $orangTuas = $query->orderBy('orang_tua.nama')->get();

        return view('Dashboard_Admin.biodata-orangtua', compact('orangTuas', 'search'));
    }

    public function create()
    {
        return view('Dashboard_Admin.biodata-orangtua-create');
    }

    public function store(Request $request)
    {
        $orangTuaRoleId = $this->getOrangTuaRoleId();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nik' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Simpan ke tabel users
        $user = User::create([
            'username' => $validated['username'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role_id' => $orangTuaRoleId,
        ]);

        // Simpan ke tabel orang_tua
        DB::table('orang_tua')->insert([
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'nik' => $validated['nik'] ?? null,
            'jenis_kelamin' => $validated['gender'] ?? null,
            'no_hp' => $validated['phone'] ?? null,
            'alamat' => $validated['address'] ?? null,
        ]);

        return redirect()->route('admin.orangtua.index')
            ->with('success', 'Data orang tua berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $orangTuaRoleId = $this->getOrangTuaRoleId();

        if ($user->role_id !== $orangTuaRoleId) {
            abort(404);
        }

        $profil = DB::table('orang_tua')->where('user_id', $user->id)->first();

        return view('Dashboard_Admin.biodata-orangtua-edit', compact('user', 'profil'));
    }

    public function update(Request $request, User $user)
    {
        $orangTuaRoleId = $this->getOrangTuaRoleId();

        if ($user->role_id !== $orangTuaRoleId) {
            abort(404);
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'username' => ['required', 'string', 'max:100', 'unique:users,username,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'nik' => ['nullable', 'string', 'max:50'],
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

        DB::table('orang_tua')->where('user_id', $user->id)->update([
            'nama' => $validated['nama'],
            'nik' => $validated['nik'] ?? null,
            'jenis_kelamin' => $validated['gender'] ?? null,
            'no_hp' => $validated['phone'] ?? null,
            'alamat' => $validated['address'] ?? null,
        ]);

        return redirect()->route('admin.orangtua.index')
            ->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $orangTuaRoleId = $this->getOrangTuaRoleId();

        if ($user->role_id !== $orangTuaRoleId) {
            abort(404);
        }

        DB::table('orang_tua')->where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('admin.orangtua.index')
            ->with('success', 'Data orang tua berhasil dihapus.');
    }
}