<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class GateService
{
    /**
     * Register gates (permissions, authorization)
     *
     * @return void
     */
    public static function register()
    {
        // 1. Intercepting Gate Checks
        // 1.1 Run before all other authorization checks
        // If returns a non-null result, result will be considered the result of the authorization check
        Gate::before(function ($user, $ability) {
            // dd($user, $ability);
        });
        // 1.2 Executed after all other authorization checks
        // If returns a non-null result, result will be considered the result of the authorization check
        Gate::after(function ($user, $ability, $result, $arguments) {
            // dd($user, $ability, $result, $arguments);
        });

        /**
         * Define a permission gate for accessing screens
         *
         * - Default middleware in laravel for Gate is `can`.
         * - Uasge:
         *   middleware(['can:access.screen']) or
         *   middleware('can:access.screen') or
         *   can('access.screen')
         */
        Gate::define('access.screen', function (User $user) {
            return core()->permission()->canAccessScreen();
        });

        /**
         * Define a permission gate for actions (CRUD)
         *
         * - Default middleware in laravel for Gate is `can`.
         * - Uasge:
         *   middleware(['can:can,"update","read","create","delete"']) or
         *   can('can', ['"update"','"read"','"create"','"delete"'])
         */
        Gate::define('can', function (User $user) {
            $actions = func_get_args();
            // Remove 1st argument (User)
            array_shift($actions);
            return core()->permission()->can($actions);
        });
    }
}
