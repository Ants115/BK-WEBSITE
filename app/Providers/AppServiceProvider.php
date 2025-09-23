<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Import class View
use App\View\Composers\NotificationComposer; // Import class NotificationComposer

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
        // Daftarkan NotificationComposer ke view 'layouts.navigation'
        // INI ADALAH TEMPAT YANG BENAR
        View::composer('layouts.navigation', NotificationComposer::class);
    }
}