<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    private function getAdminRoleId(): int
    {
        if (!Schema::hasTable('roles')) {
            abort(500, 'Tabel roles tidak tersedia.');
        }

        return DB::table('roles')->where('nama_role', 'admin')->value('id_role')
            ?: abort(500, 'Role admin tidak ditemukan.');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $adminRoleId = $this->getAdminRoleId();

        $query = DB::table('users')
            ->select(
                'users.id',
                'users.username',
                'users.email',
                'admin.id_admin',
                'admin.nama',
                'admin.nip',
                'admin.nuptk',
                'admin.jenis_kelamin as gender',
                'admin.no_hp as phone',
                'admin.alamat as address'
            )
            ->join('admin', 'admin.user_id', '=', 'users.id')
            ->where('users.role_id', $adminRoleId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('admin.nama', 'like', "%{$search}%")
                    ->orWhere('users.username', 'like', "%{$search}%")
                    ->orWhere('admin.nip', 'like', "%{$search}%")
                    ->orWhere('admin.nuptk', 'like', "%{$search}%");
            });
        }

        $admins = $query->orderBy('admin.nama')->get();

        return view('Dashboard_Admin.biodata-admin', compact('admins', 'search'));
    }

    public function create()
    {
        return view('Dashboard_Admin.biodata-admin-create');
    }

    public function store(Request $request)
    {
        $adminRoleId = $this->getAdminRoleId();

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
            'role_id' => $adminRoleId,
        ]);

        // Simpan ke tabel admin
        DB::table('admin')->insert([
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'nip' => $validated['nip'] ?? null,
            'nuptk' => $validated['nuptk'] ?? null,
            'jenis_kelamin' => $validated['gender'] ?? null,
            'no_hp' => $validated['phone'] ?? null,
            'alamat' => $validated['address'] ?? null,
        ]);

        return redirect()->route('admin.biodata.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $adminRoleId = $this->getAdminRoleId();

        if ($user->role_id !== $adminRoleId) {
            abort(404);
        }

        $profil = DB::table('admin')->where('user_id', $user->id)->first();

        return view('Dashboard_Admin.biodata-admin-edit', compact('user', 'profil'));
    }

    public function update(Request $request, User $user)
    {
        $adminRoleId = $this->getAdminRoleId();

        if ($user->role_id !== $adminRoleId) {
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

        // Update tabel users
        $userData = [
            'username' => $validated['username'],
            'email' => strtolower($validated['email']),
        ];
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $user->update($userData);

        // Update tabel admin
        DB::table('admin')->where('user_id', $user->id)->update([
            'nama' => $validated['nama'],
            'nip' => $validated['nip'] ?? null,
            'nuptk' => $validated['nuptk'] ?? null,
            'jenis_kelamin' => $validated['gender'] ?? null,
            'no_hp' => $validated['phone'] ?? null,
            'alamat' => $validated['address'] ?? null,
        ]);

        return redirect()->route('admin.biodata.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $adminRoleId = $this->getAdminRoleId();

        if ($user->role_id !== $adminRoleId) {
            abort(404);
        }

        // Hapus data profil admin dulu, baru user
        DB::table('admin')->where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('admin.biodata.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}