<?php

namespace App\View\Composers;

use Illuminate\View\View;

class PermissionComposer
{
    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * This method for view `composer`.
     * It will be executed each time the view is being rendered.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('permission', core()->permission());
    }

    /**
     * Bind data to the view.
     *
     * This method for view `creator`.
     * It will be executed immediately after the view is instantiated.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function create(View $view)
    {
        $view->with('permission', core()->permission());
    }
}
