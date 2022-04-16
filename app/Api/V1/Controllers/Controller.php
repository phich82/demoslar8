<?php

namespace App\Api\V1\Controllers;

use App\Api\ApiController;

class Controller extends ApiController
{
    /**
     * @overide For AutoValidation Class
     *
     * Get validation file path & controller namespace
     *
     * @var string
     */
    protected $validation_file_path = 'app.Api.V1.Validation.validation';
    protected $validation_controller_namespace = Controller::class;
}
