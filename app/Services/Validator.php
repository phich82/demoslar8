<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator as FacadesValidator;

class Validator
{
    /**
     * Make a validation
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function make()
    {
        $data = [];
        $rules = [];
        $messages = [];
        $customAttributes = [];

        $args = func_get_args();

        if (count($args) < 1) {
            return FacadesValidator::make(...$args);
        }

        switch (count($args)) {
            case 1:
                $data = request()->all();
                $rules = $args[0];
                break;
            case 2:
                $data = $args[0];
                $rules = $args[1];
                break;
            case 3:
                $data = $args[0];
                $rules = $args[1];
                $messages = $args[2];
                break;
            default:
                $data = $args[0];
                $rules = $args[1];
                $messages = $args[2];
                $customAttributes = $args[3];
        }

        return FacadesValidator::make($data, $rules, $messages, $customAttributes);
    }
}
