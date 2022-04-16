<?php

namespace App\Repositories;

use App\Models\Action;
use App\Repositories\Repository;

class ActionRepository extends Repository
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
        return Action::class;
    }
}
