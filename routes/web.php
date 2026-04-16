<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('Authentikasi.login');
})->name('login');

Route::post('/login', function () {
    return redirect()->route('dashboard');
})->name('login.submit');

Route::get('/dashboard', function () {
    return view('Dashboard_Guru.dashboard');
})->name('dashboard');

Route::get('/dashboard/absensi', function () {
    return view('Dashboard_Guru.absensi');
})->name('dashboard.absensi');

Route::get('/dashboard/absensi/recap', function () {
    return view('Dashboard_Guru.rekap-absensi');
})->name('dashboard.absensi.recap');

// Admin Dashboard Routes
Route::get('/admin/dashboard', function () {
    return view('Dashboard_Admin.dashboard-admin');
})->name('admin.dashboard');

Route::get('/admin/pengumuman', function () {
    return view('Dashboard_Admin.pengumuman-admin');
})->name('admin.pengumuman');

Route::get('/admin/jadwal', function () {
    return view('Dashboard_Admin.jadwal-admin');
})->name('admin.jadwal');

