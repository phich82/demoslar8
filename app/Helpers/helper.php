<?php

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return request()->is('admin/*');
    }
}

if (!function_exists('isApi')) {
    function isApi()
    {
        return request()->is('api/*');
    }
}
