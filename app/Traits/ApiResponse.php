<?php

namespace App\Traits;

use App\Helpers\Constant;
use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Response successfully
     *
     * @param  string|array|object $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($data = null, $message = '', $code = Response::HTTP_OK)
    {
        return $this->_json([
            Constant::API_RESPONSE_SUCCESS => true,
            Constant::API_RESPONSE_CODE => $code,
            Constant::API_RESPONSE_MESSAGE => $message,
            Constant::API_RESPONSE_RESULT => $data,
        ]);
    }

    /**
     * Response failed
     *
     * @param  string $message
     * @param  int $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError($message, $code = Response::HTTP_BAD_REQUEST)
    {
        return $this->_json([
            Constant::API_RESPONSE_SUCCESS => false,
            Constant::API_RESPONSE_CODE => $code,
            Constant::API_RESPONSE_MESSAGE => $message,
            Constant::API_RESPONSE_RESULT => Constant::API_RESPONSE_DEFAULT_ERROR_DATA,
        ]);
    }

    /**
     * Create a new JSON response instance.
     *
     * @param  string|array|object $result
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function _json($result)
    {
        return response()->json($result);
    }
}
