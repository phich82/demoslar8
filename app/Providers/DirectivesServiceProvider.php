<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class DirectivesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // @datetime(<format>)
        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
        });
        Blade::directive('permission', function ($expression) {
            eval("\$args = [$expression];");
            return "<?php if ($args[0] == 'admin') : ?>";
        });
        Blade::directive('endpermission', function ($role) {
            return "<?php endif; ?>";
        });

        // @validation("last_name")
        Blade::directive('validation', function ($expression) {

            // $expression is a string expression and
            // hence will be a string maintaing start and end quotes
            // Trimming the quotes
            $expression = str_replace(['(', ')'], '', $expression);
            $fieldName  = trim(trim($expression, '"'), '\'');

            $errors = Session::get('errors', new \Illuminate\Support\MessageBag);

            if ($errors->has($fieldName)) {
                return '<span role="alert"><strong>' . $errors->first($fieldName) . '</strong></span>';
            }

            return;
        });

        Blade::directive('cap', function ($expression) {
            return "<?php echo ucwords($expression); ?>";
        });

        Blade::directive('dd', function ($expression) {
            // $expression is a string expression and
            // It can not be directly passed in dd($expression)
            // We can use with() helper which basically encapsulate the syntax
            // to render it properly in blade files
            return "<?php dd(with({$expression})); ?>";
        });

        Blade::if('isadmin', function ($user) {
            return $user->role == 'admin';
        });

        Blade::if('role', function ($role) {

            // Not logged in
            if (!auth()->check()) {
                return false;
            }

            // Multiple roles passed in argument
            if (is_array($role)) {
                return in_array(auth()->user()->role, $role);
            }

            if (is_object($role)) {
                return auth()->user()->role == $role->role;
            }

            // Single role passed in argument
            return auth()->user()->role == $role;
        });
        Blade::directive('icon', function ($expression) {
            // The $expression passed in closure is a string in format '($name), value'
            // Hence we first remove the paranthesis and explode by comma
            $classes = str_replace(['(', ')'], '', $expression);
            $classes = str_replace(['"', '\''], '', $expression);
            $classes = str_replace(',', ' ', $classes);

            return '<i class="fa ' . $classes . '" aria-hidden="false"></i>';
        });

        // <x-package-alert/>
        Blade::component('package-alert', Alert::class);
    }
}
