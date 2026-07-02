<?php

namespace App\Http\Controllers\Admin\Biodata;

use App\Models\User;
use App\Models\Pengumuman;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\JadwalPelajaran;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

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

    public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        // Ambil data guru berdasarkan user_id
        $guru = DB::table('guru')->where('user_id', $user->id)->first();

        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);
        $selectedClassName = null;
        $absensiSummary = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
            'total' => 0,
            'persen' => 0,
        ];
        $absensiChart = [0, 0, 0, 0];
        $latestDokumentasi = null;
        $jadwalMengajarAktif = collect();

        if ($guru) {
            // Ambil ID kelas yang diajar oleh guru ini
            $kelasIds = Kelas::where('guru_id', $guru->id_guru)->pluck('id_kelas');
            $selectedClassId = $kelasIds->first();
            $selectedClass = Kelas::find($selectedClassId);
            $selectedClassName = $selectedClass?->nama_kelas;

            $siswaIds = $selectedClassId
                ? DB::table('siswa')->where('kelas_id', $selectedClassId)->pluck('id_siswa')
                : collect();

            $absensi = Absensi::whereIn('siswa_id', $siswaIds)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->get();

            $absensiSummary = [
                'hadir' => $absensi->where('status_kehadiran', 'H')->count(),
                'sakit' => $absensi->where('status_kehadiran', 'S')->count(),
                'izin' => $absensi->where('status_kehadiran', 'I')->count(),
                'alpha' => $absensi->where('status_kehadiran', 'A')->count(),
                'total' => $absensi->count(),
            ];
            $absensiSummary['persen'] = $absensiSummary['total'] > 0
                ? round(($absensiSummary['hadir'] / $absensiSummary['total']) * 100)
                : 0;

            $absensiChart = [
                $absensiSummary['hadir'],
                $absensiSummary['sakit'],
                $absensiSummary['izin'],
                $absensiSummary['alpha'],
            ];

            $countKelasGuru = $kelasIds->count();
            $countSiswa = $siswaIds->count();
            $countDokumentasi = Kegiatan::with(['guru', 'dokumentasi', 'kelas'])
                ->where('status', 'aktif')
                ->whereIn('kelas_id', $kelasIds)
                ->count();

            $latestDokumentasi = Kegiatan::with(['guru', 'dokumentasi', 'kelas'])
                ->where('status', 'aktif')
                ->whereIn('kelas_id', $kelasIds)
                ->orderByDesc('tanggal')
                ->first();

            $tapelAktif = TahunPelajaran::where('is_active', 1)->first();

            $jadwalMengajarAktif = $tapelAktif
                ? JadwalPelajaran::with(['jamPelajaran', 'mataPelajaran', 'kelas'])
                    ->where('id_guru', $guru->id_guru)
                    ->where('id_tapel', $tapelAktif->id_tapel)
                    ->get()
                    ->sortBy(function ($item) {
                        $hariOrder = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5];
                        return [$hariOrder[$item->hari] ?? 99, $item->jam_id ?? 9999];
                    })
                    ->values()
                : collect();
        } else {
            $countKelasGuru = 0;
            $countSiswa = 0;
            $countDokumentasi = 0;
        }

        $today = now()->format('Y-m-d');
        $kelasIds = $guru ? Kelas::where('guru_id', $guru->id_guru)->pluck('id_kelas') : collect();
        $pengumuman = Pengumuman::where(function ($query) use ($kelasIds) {
                $query->whereIn('kelas_id', $kelasIds)
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

        return view('pages.dashboard-guru', compact(
            'pengumuman',
            'selectedClassName',
            'bulan',
            'tahun',
            'absensiSummary',
            'absensiChart',
            'latestDokumentasi',
            'countKelasGuru',
            'countSiswa',
            'countDokumentasi',
            'jadwalMengajarAktif'
        ));
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
                'guru.alamat as address',
                'guru.status'
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
            'status' => ['nullable', 'string', 'in:aktif,tidak_aktif'],
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
            'status' => $validated['status'] ?? 'aktif',
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

    return response()->json([
        'success' => true,
        'message' => 'Guru berhasil dihapus.'
    ]);
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