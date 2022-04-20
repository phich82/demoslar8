<?php

namespace App\Binding;

class Binding
{
    public static function start($app = null)
    {
        app()->singleton(\App\Services\Contracts\Encrypter::class, \App\Services\Implementations\EncrypterCrypt::class);

        app()->singleton(\App\Services\Contracts\Mailer::class, \App\Services\Implementations\Mailer::class);
    }
}
