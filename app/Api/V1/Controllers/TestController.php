<?php

namespace App\Api\V1\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        return $this->responseSuccess(['api' => "I'm from v1 api."]);
    }

    public function requestMessages()
    {
        return $this->responseSuccess([
            'data' => request()->all()
        ]);
    }

    public function requestReadMessages()
    {
        return $this->responseSuccess(null);
    }
}
