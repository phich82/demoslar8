<?php

use Illuminate\Http\Request;

return [

    /*************************************************************************************
     * '<key: methods(actions)_of_controller>' => '<value:                               *
     *    Closure|                                                                       *
     *    array<[                                                                        *
     *        _data_:array|Closure,       // input data for validation                   *
     *        _rules_:array|Closure,      // rules for validation (this key is required) *
     *        _messages_:array|Closure,   // custom error messages                       *
     *        _attributes_:array|Closure  // custom attributes                           *
     *    ]>                                                                             *
     * >'                                                                                *
     *************************************************************************************/

    // action (method) as array (Closure)
    'register' => [
        // key as Closure (array)
        '_data_' => function (Request $request) {
            return [
                //
            ];
        },
        // key as array (Closure)
        '_rules_' => [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ],
        // key as array (Closure)
        '_message_' => [

        ],
        // key as array (Closure)
        '_attributes_' => [

        ],
    ],
    // action as Closure (array)
    'login_' => function (Request $request) {
        // Without all keys (_data_, _rules_, _messages_, _attributes_).
        // In this case, it is considered as `rules`,
        return [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users,email,'.$request->user()->id,
            'gender'   => 'required|in:male,female',
            'birthday' => 'required|date_format:Y-n-j',
        ];
    }
];
