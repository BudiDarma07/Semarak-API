<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; // Controller untuk login/logout
use App\Http\Controllers\AsetController;      // Controller Aset yang sudah ada
use App\Http\Controllers\LaporanController;  // Controller Laporan yang sudah ada

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rute-rute ini dimuat secara otomatis dan diberi prefix /api.
|
*/

// == RUTE PUBLIK ==
// Rute ini tidak memerlukan autentikasi.
// Aplikasi mobile akan menghubungi rute ini pertama kali untuk login.
Route::post('/login', [AuthController::class, 'login']);


// == RUTE TERPROTEKSI ==
// Semua rute di dalam grup ini WAJIB menggunakan token autentikasi.
// Ini untuk memastikan hanya user yang sudah login yang bisa mengakses data.
Route::middleware('auth:sanctum')->group(function () {
    
    // Rute untuk mendapatkan data user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rute untuk logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Endpoint untuk Resource Aset ---
    // Ini secara otomatis membuat rute-rute berikut:
    // GET    /api/aset           -> untuk menampilkan semua aset
    // POST   /api/aset           -> untuk membuat aset baru
    // GET    /api/aset/{id}      -> untuk menampilkan satu aset spesifik
    // PUT/PATCH /api/aset/{id}   -> untuk memperbarui aset
    // DELETE /api/aset/{id}      -> untuk menghapus aset
    Route::apiResource('aset', AsetController::class);

    // --- Endpoint untuk Resource Laporan ---
    // Ini juga membuat rute CRUD lengkap untuk laporan.
    Route::apiResource('laporan', LaporanController::class);
});