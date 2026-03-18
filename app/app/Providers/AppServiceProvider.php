<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();
        Paginator::defaultView('vendor.pagination.bootstrap-5');

        // En local (web), usar siempre la URL de la petición para redirects (mismo host y puerto).
        // Así, si entras por http://localhost:8080, el login redirige a 8080 y no a 8000.
        if (app()->environment('local') && $this->app->runningInConsole() === false) {
            URL::forceRootUrl(request()->getSchemeAndHttpHost());
        }
    }
}
