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
        'route_path' => base_path("app/Api{:version}/routes.php"),
        'controller_namespace' => "App\\Api{:version}\\Controllers",
        'request_path' => base_path("app/Api{:version}/Requests{:append}"),
        'request_namespace' => "App\\Api{:version}\\Requests{:append}",
        'mapping_request_append_paths' => [
            /******************************************
             *  '<route_pattern>' => '<append_path>'  *
             ******************************************/
            '#^api/v2/test*#' => 'Order/Ahamove'
        ],
    ],
    'web' => [
        'request_path' => base_path("app/Http{:version}/Requests{:append}"),
        'request_namespace' => "App\\Http{:version}\\Requests{:append}",
        'mapping_request_append_paths' => [
            /******************************************
             *  '<route_pattern>' => '<append_path>'  *
             *****************************************/
        ],
    ],
];
