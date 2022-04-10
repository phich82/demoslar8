<?php

namespace App\Providers;

use App\Helpers\Constant;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleServiceProvider extends ServiceProvider
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
        /***** Register module name (admin) *****/

        // Views
        $sourcePath = __DIR__.'/../../resources/views';
        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return "{$path}/admin";
        }, config('view.paths')), [$sourcePath]), Constant::MODULE_ADMIN);

        // Config
        $this->mergeConfigFrom(__DIR__.'/../../config/admin/admin.php', Constant::MODULE_ADMIN);

        // Language files
        $langPath = base_path('resources/lang/admin');
        if (!is_dir($langPath)) {
            $langPath = __DIR__ .'/../../resources/lang';
        }
        $this->loadTranslationsFrom($langPath, Constant::MODULE_ADMIN);

        // Factories
        $this->app->singleton(Factory::class, function () {
            return Factory::construct(__DIR__ . '/../../database/factories');
        });
    }
}
