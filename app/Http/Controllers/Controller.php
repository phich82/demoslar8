<?php

namespace App\Http\Controllers;

use App\Http\Controllers\WebController;

class Controller extends WebController
{
    /**
     * @override For AutoValidation Class
     *
     * Get validation file path & controller namespace
     *
     * @var string
     */
    protected $validation_file_path = 'app.Http.Validation.validation';
    // protected $validation_controller_class = Controller::class;
}
