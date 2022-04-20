<?php

namespace App\Services\Implementations\RabbitMQ\Horizon\Traits;

use Exception;

trait VerifyInstallHorizon
{

    public $JobDeleted = '\Laravel\Horizon\Events\JobDeleted';
    public $JobPushed = '\Laravel\Horizon\Events\JobPushed';
    public $JobReserved = '\Laravel\Horizon\Events\JobReserved';
    public $JobPayload =  'Laravel\Horizon\JobPayload';
    public $JobFailed =  'Laravel\Horizon\JobFailed';
    public $HorizonJobFailed =  'Laravel\Horizon\JobFailed';

    /**
     * Check whether the horizon has been installed yet?
     *
     * @param  string $classHorizon
     * @return bool
     * @throws \Exception
     */
    public function checkInstallHorizon($classHorizon = 'Laravel\Horizon\JobPayload')
    {
        if (empty(app()->make($classHorizon))) {
            throw new Exception("Horizon is not installed.");
        }
        return true;
    }
}
