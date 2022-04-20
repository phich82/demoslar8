<?php

namespace App\Services\Facades;

use App\Services\EncrypterService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\EncrypterService encrypt(string $payload)
 * @method static \App\Services\EncrypterService decrypt(string $encrypted)
 * @method static \App\Services\EncrypterService verifyKey(string $key)
 *
 * @see \App\Services\EncrypterService
 */
class Encrypter extends Facade
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
        return EncrypterService::class;
    }
}
