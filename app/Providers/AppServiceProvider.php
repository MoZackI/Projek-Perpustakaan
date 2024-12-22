<?php

namespace App\Providers;

use App\Http\Controllers\Buku\BukuController;
use App\Http\Middleware\RoleCheck;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Anda bisa mendaftarkan service lainnya di sini.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mendaftarkan middleware
        Route::aliasMiddleware('role', RoleCheck::class);
    }
}
