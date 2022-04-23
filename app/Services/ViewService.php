<?php

namespace App\Services;

use App\Services\Core;
use Illuminate\Support\Facades\View;

class ViewService
{
    public static function register()
    {
        // View "composer" will be executed each time the view is being rendered.
        // Attach a composer to a view
        // View::composer('welcome', \App\View\Composers\PermissionComposer::class);

        // Attach a composer to many views
        // View::composer(['welcome', 'home'], \App\View\Composers\PermissionComposer::class);

        // Attach a composer to all views
        // View::composer('*', \App\View\Composers\PermissionComposer::class);

        // View "creator" are executed immediately after the view is instantiated.
        View::creator('*', \App\View\Composers\PermissionComposer::class);
    }
}
