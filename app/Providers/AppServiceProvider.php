<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        // Préchargement des ressources Vite
        Vite::prefetch(concurrency: 3);

        // Configuration de la langue en français
        App::setLocale('fr');
        Carbon::setLocale('fr');
        setlocale(LC_TIME, 'fr_FR.UTF-8');
    }
}
