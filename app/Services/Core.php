<?php

namespace App\Services;

use App\Services\Session;
use App\Services\DBService;
use App\Services\FileService;
use App\Services\HttpService;
use App\Services\Contracts\Mailer;
use App\Services\EncrypterService;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

/**
 * This class contains core services of apllication.
 */
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

    /**
     * Get record list with pagigation (manually)
     *
     * @param  string $sql
     * @param  array $binding
     * @param  int $page
     * @param  int $perPage
     * @param  array $options
     * @return array
     */
    public static function paginate($sql, array $binding = [], $page = null, $perPage = 10, $options = [])
    {
        return DBService::paginate($sql, $binding, $page, $perPage, $options);
    }

    /**
     * Http instance
     *
     * @param  mixed $http
     * @return \App\Services\HttpService
     */
    public static function http($http = null)
    {
        // return new HttpService($http);
        return app()->make(HttpService::class, [$http]);
    }

    /**
     * Encrypter instance
     *
     * @return \App\Services\EncrypterService
     */
    public static function encrypter()
    {
        return app()->make(EncrypterService::class);
    }

    /**
     * Session instance
     *
     * @return \App\Services\Session
     */
    public static function session()
    {
        return app()->make(Session::class);
    }

    /**
     * Storage instance
     *
     * @return \App\Services\FileService
     */
    public static function storage()
    {
        return app()->make(FileService::class);
    }

    /**
     * Mailer instance
     *
     * @return \App\Services\Contracts\Mailer
     */
    public static function mailer()
    {
        return app()->make(Mailer::class);
    }
}
