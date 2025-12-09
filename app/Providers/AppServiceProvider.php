<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

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
        // Set default string length for MySQL
        Schema::defaultStringLength(191);
        
        // Use Bootstrap for pagination
        Paginator::useBootstrap();
        
        // Set locale to Indonesia
        \Carbon\Carbon::setLocale('id');
        
        // Share common data to all views
        view()->composer('*', function ($view) {
            $view->with('appName', config('app.name', 'SINVOSAR'));
            $view->with('currentYear', date('Y'));
        });
    }
}