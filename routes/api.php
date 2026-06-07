<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\TahunPelajaranController;
use App\Http\Controllers\Api\BiodataAdminController;
use App\Http\Controllers\Api\BiodataGuruController;
use App\Http\Controllers\Api\BiodataOrangTuaController;
use App\Http\Controllers\Api\BiodataSiswaController;
use App\Http\Controllers\Api\DokumentasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// =====================================
// PUBLIC ROUTES
// =====================================

Route::post('/login', [AuthController::class, 'apiLogin'])->middleware('web');

// =====================================
// PROTECTED ROUTES
// =====================================

Route::middleware(['web', 'auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'apiLogout']);

    Route::prefix('pengumuman')->group(function () {
        Route::get('/', [PengumumanController::class, 'index']);
        Route::post('/', [PengumumanController::class, 'store']);
        Route::get('/{pengumuman}', [PengumumanController::class, 'show']);
        Route::put('/{pengumuman}', [PengumumanController::class, 'update']);
        Route::delete('/{pengumuman}', [PengumumanController::class, 'destroy']);
    });

    Route::prefix('tahun-pelajaran')->group(function () {
        Route::get('/', [TahunPelajaranController::class, 'index']);
        Route::post('/', [TahunPelajaranController::class, 'store']);
        Route::get('/{id}', [TahunPelajaranController::class, 'show']);
        Route::put('/{id}', [TahunPelajaranController::class, 'update']);
        Route::delete('/{id}', [TahunPelajaranController::class, 'destroy']);
    });

    Route::prefix('admin')->group(function () {
        Route::get('/biodata', [BiodataAdminController::class, 'index']);
        Route::post('/biodata', [BiodataAdminController::class, 'store']);
        Route::get('/biodata/{user}', [BiodataAdminController::class, 'show']);
        Route::put('/biodata/{user}', [BiodataAdminController::class, 'update']);
        Route::delete('/biodata/{user}', [BiodataAdminController::class, 'destroy']);
    });

    Route::prefix('guru')->group(function () {
        Route::get('/biodata', [BiodataGuruController::class, 'index']);
        Route::post('/biodata', [BiodataGuruController::class, 'store']);
        Route::get('/biodata/{user}', [BiodataGuruController::class, 'show']);
        Route::put('/biodata/{user}', [BiodataGuruController::class, 'update']);
        Route::delete('/biodata/{user}', [BiodataGuruController::class, 'destroy']);
    });

    Route::prefix('orangtua')->group(function () {
        Route::get('/biodata', [BiodataOrangTuaController::class, 'index']);
        Route::post('/biodata', [BiodataOrangTuaController::class, 'store']);
        Route::get('/biodata/{user}', [BiodataOrangTuaController::class, 'show']);
        Route::put('/biodata/{user}', [BiodataOrangTuaController::class, 'update']);
        Route::delete('/biodata/{user}', [BiodataOrangTuaController::class, 'destroy']);
    });

    Route::prefix('siswa')->group(function () {
        Route::get('/biodata', [BiodataSiswaController::class, 'index']);
        Route::post('/biodata', [BiodataSiswaController::class, 'store']);
        Route::get('/biodata/{id}', [BiodataSiswaController::class, 'show']);
        Route::put('/biodata/{id}', [BiodataSiswaController::class, 'update']);
        Route::delete('/biodata/{id}', [BiodataSiswaController::class, 'destroy']);
    });

    Route::apiResource('dokumentasi', DokumentasiController::class);
});