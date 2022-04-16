<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Repository;

class RoleRepository extends Repository
{
    /**
     * @implement
     *
     * Get model name
     *
     * @return string
     */
    protected function model()
    {
        return Role::class;
    }
}
