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
            dd(2222);
            if ($user->isAdmin()) {
                return true;
            }
        });
        // 1.2 Executed after all other authorization checks
        // If returns a non-null result, result will be considered the result of the authorization check
        Gate::after(function ($user, $ability, $result, $arguments) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        // Define a gate for managing accessing screens
        // Return bool
        Gate::define('access-page', function (User $user) {
            return in_array($user->role, ['admin', 'management']);
        });

        // Define a gate for managing accessing screens
        // Return bool
        Gate::define('access-screen', function (User $user) {
            return in_array($user->role, ['admin', 'management']);
        });

        // Return a Response instance with error message
        Gate::define('edit-settings', function (User $user) {
            return $user->isAdmin
                        ? Response::allow()
                        : Response::deny('You must be an administrator.');
        });
    }
}
