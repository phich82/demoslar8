<?php

use App\Helpers\Constant;

/**
 * This is a place only for storing values of keys are closure functions.
 *
 * @important Not could using closure function in configuration files in laravel.
 */
return [
    'api' => [
        'prefix' => Constant::PREFIX_API,
        'pattern' => Constant::API_VERSION_FOLDER_PATTERN,
        'middleware' => Constant::MIDDLEWARE_API,
        'root_path' => base_path('app/Api'),
        'route_path' => function ($version = '') {
            return base_path(sprintf("app/Api%s/routes.php", ($version ? "/$version" : "")));
        },
        'controller_namespace' => function ($version = '') {
            return sprintf("App\\Api%s\\Controllers", ($version ? "\\$version" : ""));
        },
        'request_path' => function ($version = '', $appendPath = '') {
            return base_path(sprintf(
                "app/Api%s/Requests%s",
                ($version ? "/$version" : ""),
                ($appendPath ? "/$appendPath" : "")
            ));
        },
        'request_namespace' => function ($version = '', $appendPath = '') {
            return sprintf(
                "App\\Api%s\\Requests%s",
                ($version ? "\\{$version}" : ""),
                ($appendPath ? "\\$appendPath" : "")
            );
        },
        'mapping_request_append_paths' => [
            /******************************************
             *  '<route_pattern>' => '<append_path>'  *
             ******************************************/
            '#^api/v2/test*#' => 'Order/Ahamove'
        ],
    ],
    'web' => [
        'request_path' => function ($version = '', $appendPath = '') {
            return base_path(sprintf(
                "app/Http%s/Requests%s",
                ($version ? "/$version" : ""),
                ($appendPath ? "/$appendPath" : "")
            ));
        },
        'request_namespace' => function ($version = '', $appendPath = '') {
            return sprintf(
                "App\\Http%s\\Requests%s",
                $version ? "\\{$version}" : "",
                $appendPath ? "\\$appendPath" : ""
            );
        },
        'mapping_request_append_paths' => [
            /******************************************
             *  '<route_pattern>' => '<append_path>'  *
             *****************************************/
        ],
    ],
];
