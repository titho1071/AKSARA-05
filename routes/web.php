<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TahunPelajaranController;
use App\Http\Controllers\Guru\DokumentasiGuruController;
use App\Http\Controllers\Admin\DokumentasiAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTENTIKASI
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('Authentikasi.login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', fn() => view('pages.dashboard-admin'))->name('dashboard');

    // Biodata Admin
    Route::get('/biodata', [AdminController::class, 'index'])->name('biodata.index');
    Route::get('/biodata/create', [AdminController::class, 'create'])->name('biodata.create');
    Route::post('/biodata', [AdminController::class, 'store'])->name('biodata.store');
    Route::delete('/biodata/{user}', [AdminController::class, 'destroy'])->name('biodata.destroy');
    Route::get('/biodata/{user}/edit', [AdminController::class, 'edit'])->name('biodata.edit');
    Route::put('/biodata/{user}', [AdminController::class, 'update'])->name('biodata.update');

    // Absensi Admin
    Route::get('/absensi', fn() => view('Dashboard_Admin.Absensi.absensi-admin'))->name('absensi');
    Route::get('/absensi/rekap', fn() => view('Dashboard_Admin.Absensi.rekap-absensi'))->name('absensi.rekap');
    Route::get('/tahun-pelajaran', fn() => view('Dashboard_Admin.Lainnya.tahun-pelajaran'))->name('tahun-pelajaran');
    Route::get('/tahun-pelajaran/data', [TahunPelajaranController::class, 'index'])->name('tahun-pelajaran.data');
    Route::post('/tahun-pelajaran', [TahunPelajaranController::class, 'store'])->name('tahun-pelajaran.store');
    Route::put('/tahun-pelajaran/{id}', [TahunPelajaranController::class, 'update'])->name('tahun-pelajaran.update');
    Route::delete('/tahun-pelajaran/{id}', [TahunPelajaranController::class, 'destroy'])->name('tahun-pelajaran.destroy');

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
    Route::get('/siswa', fn() => view('Dashboard_Admin.Biodata.biodata-siswa'))->name('siswa');

    // Dokumentasi, Pengumuman, Jadwal
    Route::get('/dokumentasi', [DokumentasiAdminController::class, 'index'])->name('dokumentasi');
    Route::get('/dokumentasi/{id}', [DokumentasiAdminController::class, 'show'])->name('dokumentasi.show');
    Route::get('/pengumuman', fn() => view('Dashboard_Admin.Pengumuman.pengumuman-admin'))->name('pengumuman');
    Route::get('/mata-pelajaran', fn() => view('Dashboard_Admin.Lainnya.mata-pelajaran'))->name('mata-pelajaran');
    Route::get('/jam-pelajaran', fn() => view('Dashboard_Admin.Lainnya.jam-pelajaran'))->name('jam-pelajaran');
    Route::get('/pengumuman/create', fn() => view('Dashboard_Admin.Pengumuman.pengumuman-tambah'))->name('pengumuman.create');
    Route::get('/jadwal', fn() => view('Dashboard_Admin.Jadwal.jadwal-admin'))->name('jadwal');
});


/*
|--------------------------------------------------------------------------
| GURU
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {

    Route::get('/dashboard', fn() => view('pages.dashboard-guru'))->name('dashboard');

    // Absensi
    Route::get('/absensi', fn() => view('Dashboard_Guru.Absensi.absensi'))->name('absensi');
    Route::get('/absensi/recap', fn() => view('Dashboard_Guru.Absensi.rekap-absensi'))->name('absensi.recap');
    Route::get('/absensi/kelola', fn() => view('Dashboard_Guru.Absensi.kelola-absensi'))->name('absensi.kelola');
    Route::get('/absensi/detail', fn() => view('Dashboard_Guru.Absensi.detail-absensi'))->name('absensi.detail');

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
    Route::get('/pengumuman', fn() => view('Dashboard_Guru.Pengumuman.pengumuman'))->name('pengumuman');
});


/*
|--------------------------------------------------------------------------
| ORANG TUA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:orang_tua'])->prefix('orangtua')->name('orangtua.')->group(function () {
    Route::get('/dashboard', fn() => view('pages.dashboard-orangtua'))->name('dashboard');
});