<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Nothing here.
        
    }

    public function boot()
    {
        // Legacy project does not configure model strict mode.
        $this->app->make('db')->connection()->enableQueryLog();
    }
}
