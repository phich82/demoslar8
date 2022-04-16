<?php

namespace App\Providers;

use App\Binding\Binding;
use App\Validation\RuleExtension;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Binding
        Binding::start($this->app);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Create a macro as http service for APIs
        Http::macro('api', function () { // for web APIs
            $config = config('api.web');
            return Http::withHeaders($config['headers'])->baseUrl($config['base_url']);
        });
        Http::macro('google', function () { // for Google APIs
            $config = config('api.google');
            return Http::withHeaders($config['headers'])->baseUrl($config['base_url']);
        });

        // Register more rules defined by user
        RuleExtension::register();
    }
}
