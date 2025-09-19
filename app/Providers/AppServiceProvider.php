<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View; // 1. Import View Facade
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use App\Models\ConsultationService; // 2. Import model Service Anda
use App\Observers\UserObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gunakan Bootstrap untuk pagination
        Paginator::useBootstrap();

        // Daftarkan UserObserver untuk model User
        User::observe(UserObserver::class);

        // Paksa HTTPS hanya jika environment = production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // 3. Tambahkan View Composer untuk membagikan data layanan ke semua view
        // Ini memastikan variabel $servicesForFooter akan selalu ada di file layout/footer Anda
        View::composer('*', function ($view) {
            $view->with('servicesForFooter', ConsultationService::orderBy('title', 'asc')->get());
        });
    }
}
