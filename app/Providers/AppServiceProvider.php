<?php

namespace App\Providers;

use App\Models\Enabled;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
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

            view()->share('users', Cache::remember('users', 60, function () {
                return DB::table("users")->pluck("user_id", "id");
            }));

            view()->share('agents', Cache::remember('agents', 60, function () {
                return DB::table("agents")->pluck("name", "id");
            }));
        }

        Paginator::useBootstrap();
    }
}
