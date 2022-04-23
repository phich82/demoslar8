<?php

namespace App\Services;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Session;

class DirectiveService
{
    public static function register()
    {
        // @icon()
        Blade::directive('icon', function ($expression) {
            eval("\$args = [$expression];");
            dd($args);
            // The $expression passed in closure is a string in format '($name), value'
            // Hence we first remove the paranthesis and explode by comma
            $classes = str_replace(['(', ')'], '', $expression);
            $classes = str_replace(['"', '\''], '', $expression);
            $classes = str_replace(',', ' ', $classes);

            return '<i class="fa ' . $classes . '" aria-hidden="false"></i>';
        });
        // @datetime(<format>)
        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
        });
        // @cap("string")
        Blade::directive('cap', function ($expression) {
            return "<?php echo ucwords($expression); ?>";
        });
        // @upper("string")
        Blade::directive('upper', function ($expression) {
            return "<?php echo strtoupper($expression); ?>";
        });
        // @lower("string")
        Blade::directive('lower', function ($expression) {
            return "<?php
                \$args = [{$expression}];
                echo strtolower(\$args[0]);
            ?>";
        });
        // @dd($var)
        Blade::directive('dd', function ($expression) {
            return "<?php dd({$expression}); ?>";
        });
        // @js('demo.js')
        Blade::directive('js', function ($js) {
            $js = trim(trim($js, '\''), '"');
            return "<script src='{{asset(\"js/$js\")}}' type=\"text/javascript\"></script>";
        });
        // @includeJS('demo.js')
        Blade::directive('includeJS', function ($js) {
            $js = trim(trim($js, '\''), '"');
            return "<script src='{{asset(\"js/$js\")}}' type=\"text/javascript\"></script>";
        });
        // @importJS('demo.js')
        Blade::directive('importJS', function ($js) {
            $js = trim(trim($js, '\''), '"');
            return "<script src='{{asset(\"js/$js\")}}' type=\"text/javascript\"></script>";
        });
        // @css('demo.css')
        Blade::directive('css', function ($css) {
            $css = trim(trim($css, '\''), '"');
            return "<link href='{{asset(\"css/$css\")}}' rel='stylesheet'>";
        });
        // @includeCSS('demo.css')
        Blade::directive('includeCSS', function ($css) {
            $css = trim(trim($css, '\''), '"');
            return "<link href='{{asset(\"css/$css\")}}' rel='stylesheet'>";
        });
        // @importCSS('demo.css')
        Blade::directive('importCSS', function ($css) {
            $css = trim(trim($css, '\''), '"');
            return "<link href='{{asset(\"css/$css\")}}' rel='stylesheet'>";
        });

        // @err('field')@enderr
        Blade::if('err', function ($field) {
            $errors = Session::get('errors', new \Illuminate\Support\MessageBag);
            if ($errors->has($field)) {
                echo view('components.error', ['message' => $errors->first($field)])->render();
            }
        });
        // isadmin()@endisadmin
        Blade::if('isadmin', function () {
            // Not logged in
            if (!auth()->check()) {
                return false;
            }

            foreach (auth()->user()->roles()->get() as $role) {
                if ($role->code === 'admin') {
                    return true;
                }
            }

            return false;
        });
        // @isrole()@endisrole
        Blade::if('isRole', function ($role) {

            // Not logged in
            if (!auth()->check()) {
                return false;
            }

            $rolesUser = auth()->user()->role;
            $rolesUser = is_array($rolesUser) ? $rolesUser : [$rolesUser];

            return in_array($role, $rolesUser);
        });

        // <x-error/>
        Blade::component('error', \App\View\Components\Error::class);
    }
}
