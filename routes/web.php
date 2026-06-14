<?php

use App\Http\Controllers\Admin\Biodata\AdminController;
use App\Http\Controllers\Admin\Biodata\GuruController;
use App\Http\Controllers\Admin\Biodata\OrangTuaController;
use App\Http\Controllers\Admin\Biodata\SiswaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TahunPelajaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\Guru\DokumentasiGuruController;
use App\Http\Controllers\Guru\SiswaGuruController;
use App\Http\Controllers\Guru\AbsensiGuruController;
use App\Http\Controllers\Admin\DokumentasiAdminController;
use App\Http\Controllers\Admin\AbsensiAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\Orangtua\OrangtuaJadwalController;
use App\Http\Controllers\Orangtua\OrangtuaAbsensiController;
use App\Http\Controllers\Guru\GuruJadwalController;

/*
|--------------------------------------------------------------------------
| AUTENTIKASI
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('Authentikasi.login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/pengumuman/{pengumuman}/file', [PengumumanController::class, 'file'])->name('pengumuman.file');
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        $countAdmin = \Illuminate\Support\Facades\DB::table('admin')->count();
        $countGuru = \App\Models\Guru::count();
        $countSiswa = \App\Models\Siswa::count();
        $countOrangTua = \App\Models\OrangTua::count();
        $countKelas = \App\Models\Kelas::count();

        return view('pages.dashboard-admin', compact('countAdmin', 'countGuru', 'countSiswa', 'countOrangTua', 'countKelas'));
    })->name('dashboard');

    // Biodata Admin
    Route::get('/biodata', [AdminController::class, 'index'])->name('biodata.index');
    Route::get('/biodata/create', [AdminController::class, 'create'])->name('biodata.create');
    Route::post('/biodata', [AdminController::class, 'store'])->name('biodata.store');
    Route::delete('/biodata/{user}', [AdminController::class, 'destroy'])->name('biodata.destroy');
    Route::get('/biodata/{user}/edit', [AdminController::class, 'edit'])->name('biodata.edit');
    Route::put('/biodata/{user}', [AdminController::class, 'update'])->name('biodata.update');

    // Absensi Admin
    Route::get('/absensi', [AbsensiAdminController::class, 'index'])
        ->name('absensi');

    Route::get('/absensi/rekap', [AbsensiAdminController::class, 'recap'])
        ->name('absensi.rekap');

    Route::get(
        '/absensi/{id}/pilih-bulan',
        [AbsensiAdminController::class, 'pilihBulan']
    )->name('absensi.pilih-bulan');

    Route::get(
        '/absensi/{id}/detail/{bulan}',
        [AbsensiAdminController::class, 'detail']
    )->name('absensi.detail');
    Route::get('/tahun-pelajaran', fn() => view('Dashboard_Admin.Lainnya.tahun-pelajaran'))->name('tahun-pelajaran');
    Route::get('/tahun-pelajaran/data', [TahunPelajaranController::class, 'index'])->name('tahun-pelajaran.data');
    Route::post('/tahun-pelajaran', [TahunPelajaranController::class, 'store'])->name('tahun-pelajaran.store');
    Route::put('/tahun-pelajaran/{id}', [TahunPelajaranController::class, 'update'])->name('tahun-pelajaran.update');
    Route::put('/tahun-pelajaran/{id}/aktif',[TahunPelajaranController::class, 'setAktif'])->name('tahun-pelajaran.aktif');
    Route::delete('/tahun-pelajaran/{id}', [TahunPelajaranController::class, 'destroy'])->name('tahun-pelajaran.destroy');

    // Master Data - Kelas
    Route::get('/kelas', fn() => view('Dashboard_Admin.kelas'))->name('kelas');
    Route::get('/kelas/data', [KelasController::class, 'index'])->name('kelas.data');
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    Route::get('/kelas/guru-list', [KelasController::class, 'guruList'])->name('kelas.guru-list');

    // Master Data
    // Biodata Guru
    Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
    Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
    Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
    Route::delete('/guru/{user}', [GuruController::class, 'destroy'])->name('guru.destroy');
    Route::get('/guru/{user}/edit', [GuruController::class, 'edit'])->name('guru.edit');
    Route::put('/guru/{user}', [GuruController::class, 'update'])->name('guru.update');

    // Biodata Orang Tua
    Route::get('/orangtua', [OrangTuaController::class, 'index'])->name('orangtua.index');
    Route::get('/orangtua/create', [OrangTuaController::class, 'create'])->name('orangtua.create');
    Route::post('/orangtua', [OrangTuaController::class, 'store'])->name('orangtua.store');
    Route::delete('/orangtua/{user}', [OrangTuaController::class, 'destroy'])->name('orangtua.destroy');
    Route::get('/orangtua/{user}/edit', [OrangTuaController::class, 'edit'])->name('orangtua.edit');
    Route::put('/orangtua/{user}', [OrangTuaController::class, 'update'])->name('orangtua.update');

    // Data - Biodata siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

    // Dokumentasi, Pengumuman, Jadwal
    Route::get('/dokumentasi', [DokumentasiAdminController::class, 'index'])->name('dokumentasi');
    Route::get('/dokumentasi/{id}', [DokumentasiAdminController::class, 'show'])->name('dokumentasi.show');
    Route::get('/pengumuman', fn() => view('Dashboard_Admin.Pengumuman.pengumuman-admin'))->name('pengumuman');
    Route::get('/pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::get('/pengumuman/{pengumuman}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::get('/pengumuman/{pengumuman}', [PengumumanController::class, 'detail'])->name('pengumuman.show');
    Route::get('/mata-pelajaran', fn() => view('Dashboard_Admin.Lainnya.mata-pelajaran'))->name('mata-pelajaran');
    Route::get('/jam-pelajaran', fn() => view('Dashboard_Admin.Lainnya.jam-pelajaran'))->name('jam-pelajaran');
    Route::get('/jadwal', fn() => view('Dashboard_Admin.Jadwal.jadwal-admin'))->name('jadwal');

    // Profil
    Route::get('/profil', [AdminController::class, 'profil'])->name('profil');
    Route::put('/profil/update', [AdminController::class, 'updateProfil'])->name('profil.update');
    Route::put('/profil/foto', [AdminController::class, 'updateFoto'])->name('profil.foto');
    Route::put('/profil/akun', [AdminController::class, 'updateAkun'])->name('profil.akun');
});


/*
|--------------------------------------------------------------------------
| GURU
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {

    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');

    // Absensi
    Route::get('/absensi', [AbsensiGuruController::class, 'index'])
        ->name('absensi');

    Route::get('/absensi/{id}/pilih-bulan', function ($id) {
        $kelas = \App\Models\Kelas::with('guru', 'tahunPelajaran')
            ->findOrFail($id);

        return view(
            'Dashboard_Guru.Absensi.pilih-bulan',
            compact('kelas')
        );
    })->name('absensi.pilih-bulan');

    Route::get(
        '/absensi/{id}/kelola/{bulan}/{tanggal}',
        [AbsensiGuruController::class, 'kelola']
    )->name('absensi.kelola');

    Route::post(
        '/absensi/{id}/kelola/{bulan}/{tanggal}',
        [AbsensiGuruController::class, 'simpan']
    )->name('absensi.simpan');

    Route::get(
        '/absensi/{id}/detail/{bulan}',
        [AbsensiGuruController::class, 'detail']
    )->name('absensi.detail');

    Route::get('/absensi/recap', [AbsensiGuruController::class, 'recap'])
        ->name('absensi.recap');

    // Dokumentasi
    Route::get('/dokumentasi', [DokumentasiGuruController::class, 'index'])->name('dokumentasi.index');
    Route::get('/dokumentasi/create', [DokumentasiGuruController::class, 'create'])->name('dokumentasi.create');
    Route::post('/dokumentasi', [DokumentasiGuruController::class, 'store'])->name('dokumentasi.store');
    Route::delete('/dokumentasi/foto/{idFoto}', [DokumentasiGuruController::class, 'destroyFoto'])->name('dokumentasi.foto.destroy'); // ← sebelum {id}
    Route::get('/dokumentasi/{id}', [DokumentasiGuruController::class, 'show'])->name('dokumentasi.show');
    Route::get('/dokumentasi/{id}/edit', [DokumentasiGuruController::class, 'edit'])->name('dokumentasi.edit');
    Route::put('/dokumentasi/{id}', [DokumentasiGuruController::class, 'update'])->name('dokumentasi.update');
    Route::delete('/dokumentasi/{id}', [DokumentasiGuruController::class, 'destroy'])->name('dokumentasi.destroy');

    // Pengumuman
    Route::get('/pengumuman', fn() => view('Dashboard_Guru.Pengumuman.pengumuman-guru'))->name('pengumuman');
    Route::get('/pengumuman/create', fn() => view('Dashboard_Guru.Pengumuman.pengumuman-tambah-guru'))->name('pengumuman.create');
    Route::get('/pengumuman/{id}/edit', fn() => view('Dashboard_Guru.Pengumuman.pengumuman-edit-guru'))->name('pengumuman.edit');
    Route::get('/pengumuman/{id}', function($id) {
        $pengumuman = \App\Models\Pengumuman::with('kelas')->findOrFail($id);
        return view('Dashboard_Guru.Pengumuman.pengumuman-detail-guru', compact('pengumuman'));
    })->name('pengumuman.show');

    // Jadwal
    Route::get('/jadwal', fn() => view('Dashboard_Guru.Jadwal.jadwal-guru'))->name('jadwal');
    Route::get('/jadwal', [GuruJadwalController::class, 'index'])->name('jadwal');

    // Profil
    Route::get('/profil', [GuruController::class, 'profil'])->name('profil');
    Route::put('/profil/update', [GuruController::class, 'updateProfil'])->name('profil.update');
    Route::put('/profil/foto', [GuruController::class, 'updateFoto'])->name('profil.foto');
    Route::put('/profil/akun', [GuruController::class, 'updateAkun'])->name('profil.akun');

    // Wali Kelas - Siswa Management
    Route::get('/siswa', [SiswaGuruController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/{id}', [SiswaGuruController::class, 'show'])->name('siswa.show');
    Route::get('/siswa/{id}/edit', [SiswaGuruController::class, 'edit'])->name('siswa.edit');
    Route::put('/siswa/{id}', [SiswaGuruController::class, 'update'])->name('siswa.update');
});


/*
|--------------------------------------------------------------------------
| ORANG TUA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:orang_tua'])
    ->prefix('orangtua')
    ->name('orangtua.')
    ->group(function () {

    Route::get('/dashboard', [OrangTuaController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/absensi', [OrangtuaAbsensiController::class, 'index'])
        ->name('absensi');

    Route::get('/dokumentasi', [OrangTuaController::class, 'dokumentasi'])
        ->name('dokumentasi');

    Route::get('/dokumentasi/{id}', [OrangTuaController::class, 'dokumentasiDetail'])
        ->name('dokumentasi.detail');

    Route::get('/pengumuman', [OrangTuaController::class, 'pengumuman'])
        ->name('pengumuman');

    Route::get('/pengumuman/{id}', [OrangTuaController::class, 'pengumumanDetail'])
        ->name('pengumuman.detail');

    Route::get('/jadwal', [OrangtuaJadwalController::class, 'index'])
        ->name('jadwal');

    Route::get('/profil', [OrangTuaController::class, 'profil'])
        ->name('profil');

    Route::put('/profil/update', [OrangTuaController::class, 'updateProfil'])
        ->name('profil.update');

    Route::put('/profil/foto', [OrangTuaController::class, 'updateFoto'])
        ->name('profil.foto');

    Route::put('/profil/akun', [OrangTuaController::class, 'updateAkun'])
        ->name('profil.akun');
});


