<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BiodataGuruController extends Controller
{
    public function index(Request $request)
    {
        $this->requireAdmin($request);

        $gurus = DB::table('guru')
            ->join('users', 'users.id', '=', 'guru.user_id')
            ->where('users.role_id', $this->guruRoleId())
            ->select([
                'users.id as user_id',
                'users.username',
                'users.email',
                'guru.nama',
                'guru.nip',
                'guru.nuptk',
                'guru.jenis_kelamin as gender',
                'guru.no_hp as phone',
                'guru.alamat as address',
                'guru.status',
            ])
            ->orderBy('guru.nama')
            ->get();

        return response()->json(['status' => 'success', 'data' => $gurus]);
    }

    public function show(Request $request, User $user)
    {
        $this->requireAdmin($request);

        if ($user->role_id !== $this->guruRoleId()) {
            return response()->json(['status' => 'error', 'message' => 'Data guru tidak ditemukan.'], 404);
        }

        $profile = DB::table('guru')->where('user_id', $user->id)->first();
        if (!$profile) {
            return response()->json(['status' => 'error', 'message' => 'Data guru tidak ditemukan.'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $this->format($user, $profile)]);
    }

    public function store(Request $request)
    {
        $this->requireAdmin($request);

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
            'status' => ['nullable', 'string', 'in:aktif,tidak_aktif'],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $validated['username'],
                'email' => strtolower($validated['email']),
                'password' => Hash::make($validated['password']),
                'role_id' => $this->guruRoleId(),
            ]);

            DB::table('guru')->insert([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'nip' => $validated['nip'] ?? null,
                'nuptk' => $validated['nuptk'] ?? null,
                'jenis_kelamin' => $validated['gender'] ?? null,
                'no_hp' => $validated['phone'] ?? null,
                'alamat' => $validated['address'] ?? null,
                'status' => $validated['status'] ?? 'aktif',
            ]);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Guru berhasil ditambahkan.', 'data' => $this->format($user, DB::table('guru')->where('user_id', $user->id)->first())], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        $this->requireAdmin($request);

        if ($user->role_id !== $this->guruRoleId()) {
            return response()->json(['status' => 'error', 'message' => 'Data guru tidak ditemukan.'], 404);
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
            'status' => ['nullable', 'string', 'in:aktif,tidak_aktif'],
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
            'status' => $validated['status'] ?? 'aktif',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data guru berhasil diperbarui.', 'data' => $this->format($user, DB::table('guru')->where('user_id', $user->id)->first())]);
    }

    public function destroy(Request $request, User $user)
    {
        $this->requireAdmin($request);

        if ($user->role_id !== $this->guruRoleId()) {
            return response()->json(['status' => 'error', 'message' => 'Data guru tidak ditemukan.'], 404);
        }

        DB::table('guru')->where('user_id', $user->id)->delete();
        $user->delete();

        return response()->json(['status' => 'success', 'message' => 'Guru berhasil dihapus.']);
    }

    private function requireAdmin(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role_id !== $this->adminRoleId()) {
            abort(response()->json(['status' => 'error', 'message' => 'Hanya admin yang dapat mengakses resource ini.'], 403));
        }
    }

    private function guruRoleId()
    {
        return DB::table('roles')->where('nama_role', 'guru')->value('id_role');
    }

    private function adminRoleId()
    {
        return DB::table('roles')->where('nama_role', 'admin')->value('id_role');
    }

    private function format(User $user, $profile)
    {
        return [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'nama' => $profile->nama,
            'nip' => $profile->nip,
            'nuptk' => $profile->nuptk,
            'gender' => $profile->jenis_kelamin,
            'phone' => $profile->no_hp,
            'address' => $profile->alamat,
            'status' => $profile->status,
        ];
    }
}
