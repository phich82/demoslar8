<?php

use App\Http\Controllers\Auth\RegisterController;

/**
 * If the keys of rules contain namespace, the output must not contain `namespace` key
 * or `namespace` key as true or `namespace` as a string.
 *
 * - Output:
 *   => ['rules' => {rules}] -> All keys of `rules` array must contain namespace. This is default.
 *   => ['rules' => {rules}, 'namespace' => true] -> All keys of `rules` array must contain namespace.
 *   => ['rules' => {rules}, 'namespace' => <string>] -> All keys of `rules` array must NOT contain namespace.
 *   => ['rules' => {rules}, 'namespace' => false] -> All keys of `rules` array must NOT contain namespace.
 *
 * - Notes: Must import namespace (use <controller_namspace>) if the keys of rules contain namespace
 */
return [
    'rules' => [
        // Import rules from rule files (app.Http.Validation.auth.register-controller-rules)
        // (as `app/Http/Validation/auth/register-controller-rules.php`)
        RegisterController::class => read('app.Http.Validation.auth.register-controller-rules'),
    ],
    /**
     * The `namespace` key can be a boolean or string or missing
     *
     * - If it is missing, all keys of rule must contain namespace. This is default.
     * - If it is `true`, all keys of rule must contain namespace.
     * - If it is `false`, all keys of rule must not contain namespace.
     * - If it is a string, all keys of rule must not contain namespace.
     */
    // 'namespace' => 'App\Http\Controllers',
];
