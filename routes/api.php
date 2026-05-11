<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DokumentasiController;

// PUBLIC
Route::post('/login', [AuthController::class, 'apiLogin'])->middleware('web');

// PROTECTED
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'apiLogout']);

    Route::apiResource('dokumentasi', DokumentasiController::class);
});