<?php

namespace App\Services;

use App\Services\HttpService;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Traits\Macroable;

class CoreService
{
    use Macroable;

    public static function registerMacros()
    {
        self::macro('http', function ($http = null) {
            return app()->make(HttpService::class, [$http]);
        });

        self::macro('auth', function ($guard = null) {
            if (is_null($guard)) {
                return app(AuthFactory::class);
            }
            return app(AuthFactory::class)->guard($guard);
        });
    }
}
