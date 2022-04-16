<?php

namespace App\Repositories;

use App\Models\Screen;
use App\Repositories\Repository;

class ScreenRepository extends Repository
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
        return Screen::class;
    }
}
