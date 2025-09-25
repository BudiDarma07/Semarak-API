<?php

use App\Http\Controllers\AsetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Rute menampilkan formulir
Route::get('/laporan/create/{aset}', [LaporanController::class, 'create'])->name('laporan.create');

// Rute menampilkan halaman kedua
Route::post('/laporan/next', [LaporanController::class, 'next'])->name('laporan.next');

// Rute menyimpan data
Route::post('/laporan/store', [LaporanController::class, 'store'])->name('laporan.store');

// Rute menghapus laporan
Route::delete('/laporan/{laporan}', [LaporanController::class, 'destroy'])->name('laporan.destroy');

// Rute menampilkan halaman download
Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

// Rute mengunduh laporan
Route::get('/laporan/download', [LaporanController::class, 'download'])->name('laporan.download');

// Rute menampilkan laporan
Route::get('/laporan/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');

// Rute perubahan status
Route::put('/laporan/{laporan}/status', [LaporanController::class, 'updateStatus'])->name('laporan.updateStatus')->middleware('auth');

// Rute menampilkan login
Route::get('/login', [AuthController::class, 'show'])->name('login')->middleware('guest');

// Rute memproses data login
Route::post('/login', [AuthController::class, 'store']);

// Rute untuk logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute dashboard setelah login
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Rute Aset
Route::resource('aset', AsetController::class)->middleware('auth');

Route::get('/aset/{aset}/qrcode', [AsetController::class, 'qrcode'])->name('aset.qrcode');

// Rute untuk halaman pemindaian QR
Route::get('/scan-qr', [LaporanController::class, 'scanQr'])->name('laporan.scan');

Route::get('storage/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path('app/public/' . $folder . '/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->name('storage.file');