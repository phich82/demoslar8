<?php

namespace App\Api\V2\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    // use ApiResponse; // imported in ApiController.php

    public function index()
    {
        return $this->responseSuccess(['api' => "I'm a demo from v2 api."]);
    }
}
