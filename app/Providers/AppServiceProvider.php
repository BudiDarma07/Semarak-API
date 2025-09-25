<?php

namespace App\Providers;

use App\Models\Notifiaksi;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Izin ini HANYA untuk admin dan dinas (Edit, Hapus, Tambah)
        Gate::define('manage-aset', function (User $user) {
            return in_array($user->role->name, ['admin', 'dinas']);
        });

        // [BARU] Izin ini untuk SEMUA peran (Admin, Dinas, Teknisi)
        // Hanya untuk melihat tombol QR Code.
        Gate::define('view-qrcode', function (User $user) {
            return in_array($user->role->name, ['admin', 'dinas', 'teknisi']);
        });

        // Gate lainnya...
        Gate::define('delete-laporan', function (User $user) {
            return $user->role->name === 'admin';
        });

        Gate::define('update-status-laporan', function (User $user) {
            return $user->role->name === 'dinas';
        });

        View::composer('layouts.app', function ($view) {
            $view->with('notifikasis', Notifiaksi::latest()->get());
        });
    }
}