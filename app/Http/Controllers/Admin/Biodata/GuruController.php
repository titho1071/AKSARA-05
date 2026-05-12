<?php

namespace App\Http\Controllers\Admin\Biodata;

use App\Models\User;
use App\Models\Pengumuman;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;

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

    public function dashboard()
    {
        $user = auth()->user();
        
        // Ambil data guru berdasarkan user_id
        $guru = DB::table('guru')->where('user_id', $user->id)->first();
        
        if (!$guru) {
            // Jika data guru tidak ditemukan (mungkin baru dibuat user-nya saja)
            $pengumuman = Pengumuman::whereNull('kelas_id')->orderByDesc('created_at')->get();
            return view('pages.dashboard-guru', compact('pengumuman'));
        }

        // Ambil ID kelas yang diajar oleh guru ini
        $kelasIds = Kelas::where('guru_id', $guru->id_guru)->pluck('id_kelas');

        // Ambil pengumuman yang sesuai dengan kelas guru tersebut atau untuk semua kelas (kelas_id null)
        $pengumuman = Pengumuman::whereIn('kelas_id', $kelasIds)
            ->orWhereNull('kelas_id')
            ->orderByDesc('created_at')
            ->take(5) // Ambil 5 terbaru saja untuk dashboard
            ->get();

        return view('pages.dashboard-guru', compact('pengumuman'));
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

        return view('Dashboard_Admin.Biodata.biodata-guru', compact('gurus', 'search'));
    }

    public function create()
    {
        return view('Dashboard_Admin.Biodata.biodata-guru-create');
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

        return view('Dashboard_Admin.Biodata.biodata-guru-edit', compact('user', 'profil'));
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

        // Hapus data profil guru dulu, baru user
        DB::table('guru')->where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Guru berhasil dihapus.');
    }

    public function profil()
    {
        $user = auth()->user();
        $guru = DB::table('guru')->where('user_id', $user->id)->first();
        
        return view('Dashboard_Guru.Profil.profil-guru', compact('user', 'guru'));
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
        
        DB::table('guru')->where('user_id', $user->id)->update([
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
            $path = $request->file('foto')->store('profil-guru', 'public');
            DB::table('guru')->where('user_id', $user->id)->update([
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