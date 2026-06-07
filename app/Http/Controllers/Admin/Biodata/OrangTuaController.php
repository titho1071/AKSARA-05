<?php

namespace App\Http\Controllers\Admin\Biodata;

use App\Models\User;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;

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

    public function dashboard(Request $request)
    {
        $user = auth()->user();

        // Ambil data orang tua
        $ortu = DB::table('orang_tua')
            ->where('user_id', $user->id)
            ->first();

        $today = now()->format('Y-m-d');

        if (!$ortu) {
            return view('pages.dashboard-orangtua', [
                'siswa' => collect(),
                'activeSiswa' => null,
                'pengumuman' => Pengumuman::whereNull('kelas_id')
                    ->where(function ($query) use ($today) {
                        $query->whereNull('tanggal_mulai')->orWhereDate('tanggal_mulai', '<=', $today);
                    })->where(function ($query) use ($today) {
                        $query->whereNull('tanggal_selesai')->orWhereDate('tanggal_selesai', '>=', $today);
                    })
                    ->orderByDesc('created_at')
                    ->take(5)
                    ->get()
            ]);
        }

        // Ambil data siswa yang terkait
        $siswa = DB::table('siswa')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id_kelas')
            ->where('siswa.orang_tua_id', $ortu->id_orang_tua)
            ->select('siswa.*', 'kelas.nama_kelas')
            ->get();

        // Anak aktif
        $activeSiswaId = $request->query('siswa_id', $siswa->first()?->id_siswa);
        $activeSiswa = $siswa->where('id_siswa', $activeSiswaId)->first();

        // Ambil pengumuman terbaru
        if ($activeSiswa) {
            $pengumuman = Pengumuman::where(function ($query) use ($activeSiswa) {
                    $query->where('kelas_id', $activeSiswa->kelas_id)
                          ->orWhereNull('kelas_id');
                })
                ->where(function ($query) use ($today) {
                    $query->whereNull('tanggal_mulai')
                          ->orWhereDate('tanggal_mulai', '<=', $today);
                })
                ->where(function ($query) use ($today) {
                    $query->whereNull('tanggal_selesai')
                          ->orWhereDate('tanggal_selesai', '>=', $today);
                })
                ->orderByDesc('created_at')
                ->take(5)
                ->get();
        } else {
            $pengumuman = Pengumuman::whereNull('kelas_id')
                ->where(function ($query) use ($today) {
                    $query->whereNull('tanggal_mulai')
                          ->orWhereDate('tanggal_mulai', '<=', $today);
                })
                ->where(function ($query) use ($today) {
                    $query->whereNull('tanggal_selesai')
                          ->orWhereDate('tanggal_selesai', '>=', $today);
                })
                ->orderByDesc('created_at')
                ->take(5)
                ->get();
        }

        return view('pages.dashboard-orangtua', compact('siswa', 'activeSiswa', 'pengumuman'));
    }

    public function profil()
    {
        $user = auth()->user();
        $ortu = DB::table('orang_tua')->where('user_id', $user->id)->first();
        
        return view('Dashboard_Orangtua.Profil.profil-orangtua', compact('user', 'ortu'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $user = auth()->user();
        
        DB::table('orang_tua')->where('user_id', $user->id)->update([
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
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
            $path = $request->file('foto')->store('profil-orangtua', 'public');
            DB::table('orang_tua')->where('user_id', $user->id)->update([
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

    public function jadwal(Request $request)
    {
        $user = auth()->user();
        $ortu = DB::table('orang_tua')->where('user_id', $user->id)->first();
        
        if (!$ortu) {
            return redirect()->route('login');
        }

        // Ambil semua anak
        $siswa = DB::table('siswa')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id_kelas')
            ->where('siswa.orang_tua_id', $ortu->id_orang_tua)
            ->select('siswa.*', 'kelas.nama_kelas')
            ->get();

        // Jika tidak ada siswa, buat data statis (Front-end only mode)
        if ($siswa->isEmpty()) {
            $siswa = collect([
                (object)[ 'id_siswa' => 1, 'nama' => 'Yusuf Ahmad', 'nama_kelas' => 'Kelas III A' ],
                (object)[ 'id_siswa' => 2, 'nama' => 'Siti Aisyah', 'nama_kelas' => 'Kelas V B' ],
                (object)[ 'id_siswa' => 3, 'nama' => 'M. Rafi', 'nama_kelas' => 'Kelas I C' ],
            ]);
        }

        // Pilih siswa yang aktif
        $activeSiswaId = $request->query('siswa_id', $siswa->first()?->id_siswa);
        $activeSiswa = $siswa->where('id_siswa', $activeSiswaId)->first();

        // Data Mock untuk Jadwal
        $jadwal = [
            'Senin' => [
                ['mapel' => 'Matematika', 'jam' => '07:00 - 08:30', 'guru' => 'Budi Santoso', 'jp' => 2, 'color' => 'blue'],
                ['mapel' => 'Bahasa Indonesia', 'jam' => '08:30 - 09:15', 'guru' => 'Sari Dewi', 'jp' => 1, 'color' => 'orange'],
                ['type' => 'istirahat', 'jam' => '09:15 - 09:30'],
                ['mapel' => 'IPA', 'jam' => '09:30 - 11:00', 'guru' => 'Ahmad Fauzi', 'jp' => 2, 'color' => 'blue'],
            ],
            'Selasa' => [
                ['mapel' => 'Bahasa Inggris', 'jam' => '07:00 - 08:30', 'guru' => 'Rina Hastuti', 'jp' => 2, 'color' => 'green'],
                ['mapel' => 'IPS', 'jam' => '08:30 - 09:15', 'guru' => 'Doni Kusuma', 'jp' => 1, 'color' => 'purple'],
                ['type' => 'istirahat', 'jam' => '09:15 - 09:30'],
                ['mapel' => 'PKn', 'jam' => '09:30 - 10:15', 'guru' => 'Lena Marlina', 'jp' => 1, 'color' => 'red'],
            ],
            'Rabu' => [
                ['mapel' => 'Seni Budaya', 'jam' => '07:00 - 08:30', 'guru' => 'Fitri Handayani', 'jp' => 2, 'color' => 'amber'],
                ['type' => 'istirahat', 'jam' => '09:15 - 09:30'],
                ['mapel' => 'Matematika', 'jam' => '09:30 - 11:00', 'guru' => 'Budi Santoso', 'jp' => 2, 'color' => 'blue'],
            ],
            'Kamis' => [
                ['mapel' => 'PJOK', 'jam' => '07:00 - 08:30', 'guru' => 'Hendra Wijaya', 'jp' => 2, 'color' => 'teal'],
                ['type' => 'istirahat', 'jam' => '09:15 - 09:30'],
                ['mapel' => 'Agama', 'jam' => '09:30 - 11:00', 'guru' => 'Ummul Fitri', 'jp' => 2, 'color' => 'yellow'],
            ],
            'Jumat' => [
                ['mapel' => 'Bahasa Indonesia', 'jam' => '07:00 - 08:30', 'guru' => 'Sari Dewi', 'jp' => 2, 'color' => 'orange'],
                ['type' => 'istirahat', 'jam' => '09:15 - 09:30'],
                ['mapel' => 'Prakarya', 'jam' => '09:30 - 10:15', 'guru' => 'Sukma Evadini', 'jp' => 1, 'color' => 'pink'],
            ]
        ];

        return view('Dashboard_Orangtua.Jadwal.jadwal-orangtua', compact('siswa', 'activeSiswa', 'jadwal'));
    }

    public function pengumumanDetail($id)
    {
        $pengumuman = Pengumuman::with('kelas')->findOrFail($id);
        return view('Dashboard_Orangtua.Pengumuman.pengumuman-detail-orangtua', compact('pengumuman'));
    }

    public function pengumuman(Request $request)
    {
        $user = auth()->user();

        // Ambil data orang tua
        $ortu = DB::table('orang_tua')
            ->where('user_id', $user->id)
            ->first();

        $today = now()->format('Y-m-d');

        if (!$ortu) {
            $pengumuman = Pengumuman::whereNull('kelas_id')
                ->where(function ($query) use ($today) {
                    $query->whereNull('tanggal_mulai')
                          ->orWhereDate('tanggal_mulai', '<=', $today);
                })
                ->where(function ($query) use ($today) {
                    $query->whereNull('tanggal_selesai')
                          ->orWhereDate('tanggal_selesai', '>=', $today);
                })
                ->orderByDesc('created_at')
                ->get();
            return view(
                'Dashboard_Orangtua.Pengumuman.pengumuman-orangtua',
                [
                    'pengumuman' => $pengumuman,
                    'siswa' => collect(),
                    'activeSiswa' => null,
                ]
            );
        }

        // Ambil semua anak
        $siswa = DB::table('siswa')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id_kelas')
            ->where('siswa.orang_tua_id', $ortu->id_orang_tua)
            ->select('siswa.*', 'kelas.nama_kelas')
            ->get();

        // Anak aktif
        $activeSiswaId = $request->query(
            'siswa_id',
            $siswa->first()?->id_siswa
        );

        $activeSiswa = $siswa
            ->where('id_siswa', $activeSiswaId)
            ->first();

        // Pengumuman
        if ($activeSiswa) {
            $pengumuman = Pengumuman::with('kelas')
                ->where(function ($query) use ($activeSiswa) {
                    $query->where('kelas_id', $activeSiswa->kelas_id)
                        ->orWhereNull('kelas_id');
                })
                ->where(function ($query) use ($today) {
                    $query->whereNull('tanggal_mulai')
                          ->orWhereDate('tanggal_mulai', '<=', $today);
                })
                ->where(function ($query) use ($today) {
                    $query->whereNull('tanggal_selesai')
                          ->orWhereDate('tanggal_selesai', '>=', $today);
                })
                ->orderByDesc('created_at')
                ->get();
        } else {
            $pengumuman = Pengumuman::whereNull('kelas_id')
                ->where(function ($query) use ($today) {
                    $query->whereNull('tanggal_mulai')
                          ->orWhereDate('tanggal_mulai', '<=', $today);
                })
                ->where(function ($query) use ($today) {
                    $query->whereNull('tanggal_selesai')
                          ->orWhereDate('tanggal_selesai', '>=', $today);
                })
                ->orderByDesc('created_at')
                ->get();
        }

        return view(
            'Dashboard_Orangtua.Pengumuman.pengumuman-orangtua',
            compact('pengumuman', 'siswa', 'activeSiswa')
        );
    }

    public function dokumentasiDetail($id)
    {
        $kegiatan = \App\Models\Kegiatan::with([
            'guru',
            'dokumentasi'
        ])->findOrFail($id);

        return view(
            'Dashboard_Orangtua.Dokumentasi.dokumentasi-detail-orangtua',
            compact('kegiatan')
        );
    }

    public function dokumentasi(Request $request)
    {
        $user = auth()->user();

        $ortu = DB::table('orang_tua')
            ->where('user_id', $user->id)
            ->first();

        if (!$ortu) {
            return redirect()->route('login');
        }

        // Ambil semua anak
        $siswa = DB::table('siswa')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id_kelas')
            ->where('siswa.orang_tua_id', $ortu->id_orang_tua)
            ->select(
                'siswa.*',
                'kelas.nama_kelas'
            )
            ->get();

        $activeSiswaId = $request->query(
            'siswa_id',
            $siswa->first()?->id_siswa
        );

        $activeSiswa = $siswa
            ->where('id_siswa', $activeSiswaId)
            ->first();

        $kegiatans = collect();

        if ($activeSiswa) {
            $kelasId = $activeSiswa->kelas_id;

            $kegiatans = \App\Models\Kegiatan::with(['guru', 'dokumentasi'])
                ->where(function ($query) use ($kelasId) {
                    $query->where('kelas_id', $kelasId)
                        ->orWhere('kelas_id', 'semua_kelas');
                })
                ->orderByDesc('tanggal')
                ->get();
        }

        return view(
            'Dashboard_Orangtua.Dokumentasi.dokumentasi-orangtua',
            compact(
                'siswa',
                'activeSiswa',
                'kegiatans'
            )
        );
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
                'orang_tua.alamat as address',
                'orang_tua.status'
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

        return view('Dashboard_Admin.Biodata.biodata-orangtua', compact('orangTuas', 'search'));
    }

    public function create()
    {
        return view('Dashboard_Admin.Biodata.biodata-orangtua-create');
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
            'status' => ['nullable', 'string', 'in:aktif,tidak_aktif'],
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
            'status' => $validated['status'] ?? 'aktif',
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

        return view('Dashboard_Admin.Biodata.biodata-orangtua-edit', compact('user', 'profil'));
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

    return response()->json([
        'success' => true,
        'message' => 'Data orang tua berhasil dihapus.'
    ]);
}
}