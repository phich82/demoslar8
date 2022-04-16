<?php

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Api\V2\Controllers\TestController;

/**
 * If keys contain namespace, output must not contain `namespace` key or `namespace` key as true or `namespace` as a string
 * - Output:
 *   => ['rules' => {rules}] -> All keys of `rules` array must contain namespace. This is default.
 *   => ['rules' => {rules}, 'namespace' => true] -> All keys of `rules` array must contain namespace.
 *   => ['rules' => {rules}, 'namespace' => <string>] -> All keys of `rules` array must NOT contain namespace.
 *   => ['rules' => {rules}, 'namespace' => false] -> All keys of `rules` array must NOT contain namespace.
 */
$rules = [
    'UserController' => [
        'register' => [
            'name'     => 'required|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->where('status', 1)],
            'password' => 'required|min:6|confirmed',
            'gender'   => 'required|in:male,female',
            'birthday' => 'required|date_format:Y-n-j',
        ],
        'update' => function ($request) {
            return [
                'name'     => 'required|max:255',
                'email'    => 'required|email|max:255|unique:users,email,'.$request->user()->id,
                'gender'   => 'required|in:male,female',
                'birthday' => 'required|date_format:Y-n-j',
            ];
        }
    ],
    'ResetPasswordController' => [
        'getResetMethods' => [
            'keyword' => 'required',
        ],
        'sendToken' => [
            'method'  => 'required|in:mail,phone_number',
            'keyword' => 'required',
        ],
        'reset' => [
            'token'    => 'required',
            'password' => 'required|min:6|confirmed',
        ],
    ],
    'Student\LessonsController' => [
        'create' => [
            'date'        => 'required|date_format:Y-n-j',
            'time'        => 'required|date_format:H:i',
            'duration'    => 'required',
            'teacher_id'  => 'required',
            'language_id' => 'required',
        ],
    ],

    TestController::class => [
        // array|Closure
        'index' => [
            // array|Closure
            '_data_' => function (Request $request) {
                return [

                ];
            },
            // array|Closure
            '_rules_' => [
                'name'     => 'required|max:255',
                'email'    => ['required', 'email', 'max:255', Rule::unique('users')->where('status', 1)],
                'password' => 'required|min:6|confirmed',
                'gender'   => 'required|in:male,female',
                'birthday' => 'required|date_format:Y-n-j',
            ],
            // array|Closure
            '_message_' => [

            ],
            '_attributes_' => [

            ],
        ],
        'update' => function (Request $request) {
            // Without keys (_data_, _rules_, _messages_, _attributes_)
            return [
                'name'     => 'required|max:255',
                'email'    => 'required|email|max:255|unique:users,email,'.$request->user()->id,
                'gender'   => 'required|in:male,female',
                'birthday' => 'required|date_format:Y-n-j',
            ];
        }
    ],
    'DemoController' => read('app.Api.V2.Validation.rules.DemoControllerRules'),
];

return [
    'rules' => $rules,
    // 'namespace' => 'App\Api\V2\Controllers', // bool<true,false>|string. Default: always has namespace
];
