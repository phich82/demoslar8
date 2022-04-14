<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Repository;

class UserRepository extends Repository
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
        return User::class;
    }
}
