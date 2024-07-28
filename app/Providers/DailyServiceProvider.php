<?php

namespace App\Providers;

use App\Models\AutoAdd;
use App\Services\Daily\AutoService;
use App\Services\Daily\PaymentRejectService;
use Illuminate\Support\ServiceProvider;
use App\Services\Daily\LuckyNumberService;

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
        if (!$this->app->runningInConsole()) {

            (new LuckyNumberService())->handle();

            if (AutoAdd::whereDate('date', today())->doesntExist()) {
                (new AutoService())->handle();
            }

        }
    }
}
