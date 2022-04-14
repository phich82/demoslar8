<?php

namespace App\Services;

use App\Models\Role;
use App\Policies\RolePolicy;

class PolicyService
{
    const POLICIES = [
        Role::class => RolePolicy::class,
    ];
}
