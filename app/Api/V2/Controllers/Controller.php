<?php

namespace App\Api\V2\Controllers;

use App\Api\ApiController;

class Controller extends ApiController
{
    /**
     * @override For AutoValidation Class
     *
     * Get validation file path & controller namespace
     *
     * @var string
     */
    protected $validation_file_path = 'app.Api.V2.Validation.validation';
    protected $validation_controller_class = Controller::class;
}
