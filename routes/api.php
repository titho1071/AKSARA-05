<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\TahunPelajaranController;
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

    Route::apiResource('dokumentasi', DokumentasiController::class);
});