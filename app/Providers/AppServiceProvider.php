<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Apis\Twitch;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public', function() {
            return base_path().'/html';
        });

        $this->app->singleton('twitch', function($app)
        {
            return new Twitch();
        });
    }
}
