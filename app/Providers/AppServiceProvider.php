<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Log;

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

    public function boot()
    {
        if(env('DB_LOGGING', false) === true) {
            DB::listen(function($sql, $bindings, $time){
                Log::info($sql, $bindings, $time);
            });
        }
    }
}
