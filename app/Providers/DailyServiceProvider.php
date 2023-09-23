<?php

namespace App\Providers;

use App\Models\AutoAdd;
use App\Services\Daily\AutoService;
use Illuminate\Support\ServiceProvider;

class DailyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->runningInConsole() && AutoAdd::whereDate('date',today())->doesntExist()) {
            (new AutoService())->handle();
        }
    }
}
