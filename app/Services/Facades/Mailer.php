<?php

namespace App\Services\Facades;

use App\Services\Contracts\Mailer as MailerContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void send(string $to, $params = [])
 *
 * @see \App\Services\EncrypterService
 */
class Mailer extends Facade
{
    /**
     * @override
     *
     * Get Facade Accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return MailerContract::class;
    }
}
