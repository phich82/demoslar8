<?php

namespace App\Services;

use stdClass;
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
     * Get permission
     *
     * @param \Illuminate\Database\Eloquent\Collection $roles
     * @return object
     */
    public static function permission($roles = null)
    {
        if (!auth()->check()) {
            return optional(null);
        }

        $roles = $roles ?: auth()->user()->roles()->get();

        // Merge permissions if user has many roles
        $permission = new stdClass();
        foreach ($roles as $role) {
            if (empty($role->permissions)) {
                return optional(true);
            }
            foreach ($role->permissions as $type => $value) {
                if ($type === 'actions') {
                    $permission->{$type} = array_unique(array_merge($permission->{$type} ?? [], $value ?: []));
                    continue 2;
                }

                if (!isset($permission->{$type})) {
                    $permission->{$type} = new stdClass();
                }

                foreach ($value as $route => $actions) {
                    if (!isset($permission->{$type}->{$route})) {
                        $permission->{$type}->{$route} = null;
                    }
                    if (!empty($actions)) {
                        $permission->{$type}->{$route} = array_unique(array_merge(
                            $permission->{$type}->{$route} ?: [],
                            $actions
                        ));
                    }
                }
            }
        }

        // Check permission for accessing to screen and actions
        $currentRouteName = request()->route()->getName();

        // Create a new object with two `canAccessScreen` and `can` properties
        foreach ($permission->screens as $route => $actions) {
            if (preg_match("#^$route$#", $currentRouteName)) {
                if (empty($actions)) {
                    $actions = $permission->actions;
                }
                $canAccessScreen = true;
                return new class ($actions, $canAccessScreen) {
                    private $actions = [];
                    private $canAccessScreen = false;

                    /**
                     * Create an instance
                     *
                     * @param array $actions
                     * @param boolean $canAccessScreen
                     * @return void
                     */
                    public function __construct($actions = [], $canAccessScreen = false)
                    {
                        $this->actions = $actions;
                        $this->canAccessScreen = $canAccessScreen;
                    }

                    /**
                     * Check whether user have permission to access screen
                     *
                     * @return boolean
                     */
                    public function canAccessScreen()
                    {
                        return $this->canAccessScreen;
                    }

                    /**
                     * User can do actions (CRUD) on screen
                     *
                     * @param string $action
                     * @return boolean
                     */
                    public function can($action = null)
                    {
                        $action = explode(',', $action);
                        return count(array_intersect($action, $this->actions)) === count($action);
                    }
                };
            }
        }

        return new class () {
            /**
             * Check whether user have permission to access screen
             *
             * @return boolean
             */
            public function canAccessScreen()
            {
                return false;
            }
            /**
             * User can do actions (CRUD) on screen
             *
             * @param string $action
             * @return boolean
             */
            public function can($action = null)
            {
                return false;
            }
        };
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
