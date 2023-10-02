<?php

namespace App\Providers;

use App\Models\Enabled;
use Illuminate\Pagination\Paginator;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->runningInConsole()){
            view()->share('enabled', Enabled::first());
        }

        Paginator::useBootstrap();
    }
}
