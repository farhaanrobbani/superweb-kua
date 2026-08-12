<?php

namespace App\Providers;

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
        $explicit = env('APP_FORCE_HTTPS');

        if ($explicit === null) {
            $forceHttps = ! app()->environment('local', 'testing');
        } else {
            $forceHttps = filter_var($explicit, FILTER_VALIDATE_BOOLEAN);
        }

        if ($forceHttps) {
            URL::forceScheme('https');
        }
    }
}
