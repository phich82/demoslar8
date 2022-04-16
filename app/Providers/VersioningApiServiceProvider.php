<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class VersioningApiServiceProvider extends ServiceProvider
{
    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes(function () {
            $this->versioningApiRoutes();
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    private function versioningApiRoutes()
    {
        // Get basic information from config file
        $config = config('version.api');
        if (empty($config)) {
            throw new Exception("Missing `config/version.php` file or `api` key.");
        }
        $middleware = $config['middleware'] ?? 'api';
        $versionPattern = $config['pattern'] ?? '#^(V|Ver|Version)\d+$#';

        // Get api versions from scaning api directory
        $versions = array_reduce(scandir($config['root_path']) ?: [], function ($carry, $name) use ($versionPattern) {
            if (preg_match("{$versionPattern}", $name)) {
                $carry[] = $name;
            }
            return $carry;
        }, []);

        // Setup routes for APIs by versions
        foreach ($versions as $version) {
            $version = trim($version);
            $prefix = trim($config['prefix'] ?? 'api', '/\\')."/".strtolower($version);
            $namespace = $config['controller_namespace'] ?? "App\\Api\\{$version}\\Controllers";
            $routePath = $config['route_path'] ?? base_path("routes".DIRECTORY_SEPARATOR."api_".strtolower($version).".php"); // routes/api_v1.php

            if (is_callable($namespace)) {
                $namespace = $namespace($version);
            }

            if (is_callable($routePath)) {
                $routePath = $routePath($version);
            }

            Route::prefix($prefix)
                ->middleware($middleware)
                ->namespace($namespace)
                ->group($routePath);
        }
    }
}
