<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Factory as AuthFactory;

class Core
{
    /**
     * Get the available auth instance
     *
     * @param  string $guard
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public static function auth($guard = null)
    {
        if (is_null($guard)) {
            return app(AuthFactory::class);
        }
        return app(AuthFactory::class)->guard($guard);
    }
}
