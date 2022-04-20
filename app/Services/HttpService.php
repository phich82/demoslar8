<?php

namespace App\Services;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Http;

class HttpService
{
    use ApiResponse;

    private $http;

    /**
     * __construct
     *
     * @param  mixed $http
     * @return void
     */
    public function __construct($http = null)
    {
        $this->http = $http;
        if (!$this->http) {
            $this->http = Http::api();
        }
    }

    /**
     * __call
     *
     * @param  mixed $method
     * @param  mixed $arguments
     * @return \object
     * @throws \Exception
     */
    public function __call($method, $arguments = [])
    {
        if (method_exists($this->http, $method)) {
            return $this->_resolveResponse($this->http->$method(...$arguments));
        }
        throw new Exception("Method [{$method}] not exists.");
    }

    /**
     * Set base url
     *
     * @param  string $baseUrl
     * @return \HttpService
     */
    public function baseUrl($baseUrl = '')
    {
        $this->http->baseUrl($baseUrl);

        return $this;
    }

    /**
     * Set new http instance
     *
     * @param  mixed $http
     * @return \HttpService
     */
    public function withHttp($http)
    {
        $this->http = $http;

        return $this;
    }

    /**
     * Resolve response
     *
     * @param  \Illuminate\Http\JsonResponse $response
     * @return \object
     */
    private function _resolveResponse($response)
    {
        if ($response->failed()) {
            return $this->responseError($response->getReasonPhrase(), $response->status())->getData();
        }
        return $this->responseSuccess($response->json(), $response->getReasonPhrase(), $response->status())->getData();
    }
}
