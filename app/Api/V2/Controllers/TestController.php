<?php

namespace App\Api\V2\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TestController extends Controller
{
    // use ApiResponse; // imported in ApiController.php

    public function index()
    {
        return $this->responseSuccess(['api' => "I'm a test from v2 api."]);
    }
}
