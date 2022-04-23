<?php

namespace App\Services;

use Exception;
use App\Services\HttpService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

/**
 * Create a macroable class
 *
 * Usage: MacroService::macro(string $name, callable $macro);
 */
class MacroService
{
    use Macroable;

    /**
     * Register more `macro` functions for available classes
     *
     * @return void
     */
    public static function register()
    {
        // Create a macro as http service for APIs
        Http::macro('api', function () { // for web APIs
            $config = config('api.web');
            if (empty($config)) {
                throw new Exception("Missing `config/api.php` file or `web` key.");
            }
            return Http::withHeaders($config['headers'])->baseUrl($config['base_url']);
        });
        Http::macro('google', function () { // for Google APIs
            $config = config('api.google');
            if (empty($config)) {
                throw new Exception("Missing `config/api.php` file or `google` key.");
            }
            return Http::withHeaders($config['headers'])->baseUrl($config['base_url']);
        });
    }
}
