<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\UserService', function ($app) {
            return new \App\Services\UserService();
        });
        $this->app->bind('App\Services\CardService', function ($app) {
            return new \App\Services\CardService();
        });
    }
}
