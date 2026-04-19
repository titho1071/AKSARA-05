<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\AuthController;
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

    // Dashboard
    Route::get('/dashboard', fn() => view('pages.dashboard-admin'))->name('dashboard');

    // Master Data - Biodata Admin
    Route::get('/biodata', [AdminController::class, 'index'])->name('biodata.index');
    Route::get('/biodata/create', [AdminController::class, 'create'])->name('biodata.create');
    Route::post('/biodata', [AdminController::class, 'store'])->name('biodata.store');
    Route::delete('/biodata/{user}', [AdminController::class, 'destroy'])->name('biodata.destroy');
    Route::get('/biodata/{user}/edit', [AdminController::class, 'edit'])->name('biodata.edit');
    Route::put('/biodata/{user}', [AdminController::class, 'update'])->name('biodata.update');

    // Master Data - Biodata Guru
    Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
    Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
    Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
    Route::delete('/guru/{user}', [GuruController::class, 'destroy'])->name('guru.destroy');
    Route::get('/guru/{user}/edit', [GuruController::class, 'edit'])->name('guru.edit');
    Route::put('/guru/{user}', [GuruController::class, 'update'])->name('guru.update');

    // Master Data - Biodata Orang Tua
    Route::get('/orangtua', [OrangTuaController::class, 'index'])->name('orangtua.index');
    Route::get('/orangtua/create', [OrangTuaController::class, 'create'])->name('orangtua.create');
    Route::post('/orangtua', [OrangTuaController::class, 'store'])->name('orangtua.store');
    Route::delete('/orangtua/{user}', [OrangTuaController::class, 'destroy'])->name('orangtua.destroy');
    Route::get('/orangtua/{user}/edit', [OrangTuaController::class, 'edit'])->name('orangtua.edit');
    Route::put('/orangtua/{user}', [OrangTuaController::class, 'update'])->name('orangtua.update');

    // Pengumuman & Jadwal
    Route::get('/pengumuman', fn() => view('Dashboard_Admin.pengumuman-admin'))->name('pengumuman');
    Route::get('/jadwal', fn() => view('Dashboard_Admin.jadwal-admin'))->name('jadwal');
});


/*
|--------------------------------------------------------------------------
| GURU
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {

    // Dashboard
    Route::get('/dashboard', fn() => view('pages.dashboard-guru'))->name('dashboard');

    // Absensi
    Route::get('/absensi', fn() => view('Dashboard_Guru.absensi'))->name('absensi');
    Route::get('/absensi/recap', fn() => view('Dashboard_Guru.rekap-absensi'))->name('absensi.recap');
    Route::get('/absensi/kelola', fn() => view('Dashboard_Guru.kelola-absensi'))->name('absensi.kelola');
    Route::get('/absensi/detail', fn() => view('Dashboard_Guru.detail-absensi'))->name('absensi.detail');
});


/*
|--------------------------------------------------------------------------
| ORANG TUA
| (uncomment saat dashboard orang tua sudah siap)
|--------------------------------------------------------------------------
*/
// Route::middleware(['auth', 'role:orang_tua'])->prefix('orangtua')->name('orangtua.')->group(function () {
//     Route::get('/dashboard', fn() => view('pages.dashboard-orangtua'))->name('dashboard');
// });


/*
|--------------------------------------------------------------------------
| UNIVERSAL (semua role yang sudah login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dokumentasi', fn() => view('Dashboard_Admin.dokumentasi'))->name('dokumentasi');
    Route::get('/pengumuman', fn() => view('Dashboard_Admin.pengumuman'))->name('pengumuman');
    Route::get('/jadwal', fn() => view('Dashboard_Admin.jadwal'))->name('jadwal');
});