<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class RegisterCommonEventsServiceProvider extends ServiceProvider
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
        // Listening For Query Events
        DB::listen(function ($query) {
            // dd('queury', $query);
            // $query->sql;
            // $query->bindings;
            // $query->time;
        });
    }
}
