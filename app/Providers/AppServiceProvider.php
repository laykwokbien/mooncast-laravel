<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Ip_address;

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
        // Ip_address::setAddress(ip: "192.168.1.8", port: 5000);
    }
}
