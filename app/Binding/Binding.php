<?php

namespace App\Binding;

class Binding
{
    public static function start($app = null)
    {
        app()->singleton(\App\Contracts\Encrypter::class, \App\Services\Implementations\EncrypterCrypt::class);
    }
}
