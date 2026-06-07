<?php

namespace App\Http\Controllers\Admin\Biodata;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;

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
                'admin.alamat as address',
                'admin.status'
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

        return view('Dashboard_Admin.Biodata.biodata-admin', compact('admins', 'search'));
    }

    public function create()
    {
        return view('Dashboard_Admin.Biodata.biodata-admin-create');
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
            'status' => ['nullable', 'string', 'in:aktif,tidak_aktif'],
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
            'status' => $validated['status'] ?? 'aktif',
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

        return view('Dashboard_Admin.Biodata.biodata-admin-edit', compact('user', 'profil'));
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
            'status' => ['nullable', 'string', 'in:aktif,tidak_aktif'],
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
            'status' => $validated['status'] ?? 'aktif',
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

    DB::table('admin')->where('user_id', $user->id)->delete();
    $user->delete();

    return response()->json([
        'success' => true,
        'message' => 'Admin berhasil dihapus.'
    ]);
}

    public function profil()
    {
        $user = auth()->user();
        $admin = DB::table('admin')->where('user_id', $user->id)->first();
        
        return view('Dashboard_Admin.Profil.profil-admin', compact('user', 'admin'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'nip' => 'nullable|string|max:50',
            'nuptk' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = auth()->user();
        
        DB::table('admin')->where('user_id', $user->id)->update([
            'nama' => $request->nama,
            'jenis_kelamin' => $request->gender,
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'no_hp' => $request->phone,
            'alamat' => $request->address,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();
        
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profil-admin', 'public');
            DB::table('admin')->where('user_id', $user->id)->update([
                'foto_profil' => $path
            ]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui');
    }

    public function updateAkun(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . auth()->id(),
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::findOrFail(auth()->id());
        
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $message = 'Data akun berhasil diperbarui.';
        if ($request->filled('password')) {
            $message .= ' Silakan gunakan password baru Anda untuk login berikutnya.';
        }

        return back()->with('success', $message);
    }
}