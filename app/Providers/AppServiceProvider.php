<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (request()->server->has('HTTP_X_FORWARDED_PROTO')) {
            if (request()->header('X-Forwarded-Proto') == 'https') {
                \URL::forceScheme('https');
            }
        }
    }
}