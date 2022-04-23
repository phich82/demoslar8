<?php

namespace App\Providers;

use Exception;
use App\Binding\Binding;
use App\Services\DirectiveService;
use App\Services\GateService;
use App\Services\MacroService;
use App\Validation\ExtensionRule;
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
        // Register macros
        MacroService::register();

        // Register more rules defined by user
        ExtensionRule::register();

        // Register blades (directives)
        DirectiveService::register();

        // Register gates
        GateService::register();
    }
}
