<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine if the given role can be viewed by the user.
     * viewAny() = index() (`index` method in controller)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return bool
     */
    public function viewAny(User $user, Role $role)
    {
        return in_array($user->role, $role->roles);

        // return in_array($user->role, $role->roles)
        //         ? Response::allow()
        //         : Response::deny('You do not own this role.');
    }

    /**
     * Determine if the given role can be viewed by the user.
     * view() = show() (`show` method in controller)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return bool
     */
    public function view(User $user, Role $role)
    {
        return in_array($user->role, $role->roles);

        // return in_array($user->role, $role->roles)
        //         ? Response::allow()
        //         : Response::deny('You do not own this role.');
    }

    /**
     * Determine if the given role can be viewed show page by the user.
     * create() = create() or store() (`show` or `store` method in controller)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return bool
     */
    public function create(User $user, Role $role)
    {
        return in_array($user->role, $role->roles);

        // return in_array($user->role, $role->roles)
        //         ? Response::allow()
        //         : Response::deny('You do not own this role.');
    }

    /**
     * Determine if the given role can be updated by the user.
     * update() = update() or edit() (`show` or `store` method in controller)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return bool
     */
    public function update(User $user, Role $role)
    {
        return in_array($user->role, $role->roles);

        // return in_array($user->role, $role->roles)
        //         ? Response::allow()
        //         : Response::deny('You do not own this role.');
    }

    /**
     * Determine if the given role can be viewed show page by the user.
     * delete() = destroy() (`destroy` method in controller)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return bool
     */
    public function delete(User $user, Role $role)
    {
        return in_array($user->role, $role->roles);

        // return in_array($user->role, $role->roles)
        //         ? Response::allow()
        //         : Response::deny('You do not own this role.');
    }
}
