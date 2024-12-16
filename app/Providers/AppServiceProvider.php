<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         /**
         * Publish asset adminlte
         * php artisan vendor:publish --tag=adminlte
         */
        $this->publishes([
            __DIR__ . '/../../vendor/almasaeed2010/adminlte/dist/css' => public_path('assets/adminlte/css'),
            __DIR__ . '/../../vendor/almasaeed2010/adminlte/dist/js' => public_path('assets/adminlte/js'),
            __DIR__ . '/../../vendor/almasaeed2010/adminlte/plugins' => public_path('assets/adminlte/plugins')
        ], 'adminlte');

        Paginator::useBootstrap();
    }
}
