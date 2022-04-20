<?php

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Api\V2\Controllers\DemoController;
use App\Api\V2\Controllers\TestController;

/**
 * If the keys of rules contain namespace, the output must not contain `namespace` key
 * or `namespace` key as true or `namespace` as a string.
 *
 * - Output:
 *   => ['rules' => {rules}] -> All keys of `rules` array must contain namespace. This is default.
 *   => ['rules' => {rules}, 'namespace' => true] -> All keys of `rules` array must contain namespace.
 *   => ['rules' => {rules}, 'namespace' => <string>] -> All keys of `rules` array must NOT contain namespace.
 *   => ['rules' => {rules}, 'namespace' => false] -> All keys of `rules` array must NOT contain namespace.
 */
$rules = [
    TestController::class => [

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
        'index' => [
            // key as Closure (array)
            '_data_' => function (Request $request) {
                return [

                ];
            },
            // key as array (Closure)
            '_rules_' => [
                'name'     => 'required|max:255',
                'email'    => ['required', 'email', 'max:255', Rule::unique('users')->where('status', 1)],
                'password' => 'required|min:6|confirmed',
                'gender'   => 'required|in:male,female',
                'birthday' => 'required|date_format:Y-n-j',
            ],
            // key as array (Closure)
            '_message_' => [

            ],
            // key as array (Closure)
            '_attributes_' => [

            ],
        ],
        // action as Closure (array)
        'update' => function (Request $request) {
            // Without all keys (_data_, _rules_, _messages_, _attributes_).
            // In this case, it is considered as `rules`,
            return [
                'name'     => 'required|max:255',
                'email'    => 'required|email|max:255|unique:users,email,'.$request->user()->id,
                'gender'   => 'required|in:male,female',
                'birthday' => 'required|date_format:Y-n-j',
            ];
        }
    ],
    DemoController::class => read('app.Api.V2.Validation.rules.DemoControllerRules'),
];

return [
    'rules' => $rules,
    /**
     * The `namespace` key can be a boolean or string or missing
     *
     * - If it is missing, all keys of rule must contain namespace. This is default.
     * - If it is `true`, all keys of rule must contain namespace.
     * - If it is `false`, all keys of rule must not contain namespace.
     * - If it is a string, all keys of rule must not contain namespace.
     */
    // 'namespace' => 'App\Api\V2\Controllers',
];
