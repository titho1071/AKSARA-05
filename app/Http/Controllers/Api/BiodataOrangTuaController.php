<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BiodataOrangTuaController extends Controller
{
    public function index(Request $request)
    {
        $this->requireAdmin($request);

        $parents = DB::table('orang_tua')
            ->join('users', 'users.id', '=', 'orang_tua.user_id')
            ->where('users.role_id', $this->orangTuaRoleId())
            ->select([
                'users.id as user_id',
                'users.username',
                'users.email',
                'orang_tua.nama',
                'orang_tua.nik',
                'orang_tua.jenis_kelamin as gender',
                'orang_tua.no_hp as phone',
                'orang_tua.alamat as address',
                'orang_tua.status',
            ])
            ->orderBy('orang_tua.nama')
            ->get();

        return response()->json(['status' => 'success', 'data' => $parents]);
    }

    public function show(Request $request, User $user)
    {
        $this->requireAdmin($request);

        if ($user->role_id !== $this->orangTuaRoleId()) {
            return response()->json(['status' => 'error', 'message' => 'Data orang tua tidak ditemukan.'], 404);
        }

        $profile = DB::table('orang_tua')->where('user_id', $user->id)->first();
        if (!$profile) {
            return response()->json(['status' => 'error', 'message' => 'Data orang tua tidak ditemukan.'], 404);
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
            'nik' => ['nullable', 'string', 'max:50'],
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
                'role_id' => $this->orangTuaRoleId(),
            ]);

            DB::table('orang_tua')->insert([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'nik' => $validated['nik'] ?? null,
                'jenis_kelamin' => $validated['gender'] ?? null,
                'no_hp' => $validated['phone'] ?? null,
                'alamat' => $validated['address'] ?? null,
                'status' => $validated['status'] ?? 'aktif',
            ]);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Orang tua berhasil ditambahkan.', 'data' => $this->format($user, DB::table('orang_tua')->where('user_id', $user->id)->first())], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        $this->requireAdmin($request);

        if ($user->role_id !== $this->orangTuaRoleId()) {
            return response()->json(['status' => 'error', 'message' => 'Data orang tua tidak ditemukan.'], 404);
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

        DB::table('orang_tua')->where('user_id', $user->id)->update([
            'nama' => $validated['nama'],
            'nik' => $validated['nik'] ?? null,
            'jenis_kelamin' => $validated['gender'] ?? null,
            'no_hp' => $validated['phone'] ?? null,
            'alamat' => $validated['address'] ?? null,
            'status' => $validated['status'] ?? 'aktif',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data orang tua berhasil diperbarui.', 'data' => $this->format($user, DB::table('orang_tua')->where('user_id', $user->id)->first())]);
    }

    public function destroy(Request $request, User $user)
    {
        $this->requireAdmin($request);

        if ($user->role_id !== $this->orangTuaRoleId()) {
            return response()->json(['status' => 'error', 'message' => 'Data orang tua tidak ditemukan.'], 404);
        }

        DB::table('orang_tua')->where('user_id', $user->id)->delete();
        $user->delete();

        return response()->json(['status' => 'success', 'message' => 'Orang tua berhasil dihapus.']);
    }

    private function requireAdmin(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role_id !== $this->adminRoleId()) {
            abort(response()->json(['status' => 'error', 'message' => 'Hanya admin yang dapat mengakses resource ini.'], 403));
        }
    }

    private function orangTuaRoleId()
    {
        return DB::table('roles')->where('nama_role', 'orang_tua')->value('id_role');
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
            'nik' => $profile->nik,
            'gender' => $profile->jenis_kelamin,
            'phone' => $profile->no_hp,
            'address' => $profile->alamat,
            'status' => $profile->status,
        ];
    }
}
