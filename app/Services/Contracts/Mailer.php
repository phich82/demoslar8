<?php

namespace App\Services\Contracts;

interface Mailer
{
    /**
     * Send email
     *
     * @param  string|array $to
     * @param  array $params
     * @return array
     */
    public function send($to, $params = []);
}
