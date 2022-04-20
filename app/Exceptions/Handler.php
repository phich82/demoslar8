<?php

namespace App\Exceptions;

use Throwable;
use PDOException;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Error messages
     *
     * @var array
     */
    protected static $errorMessages = [
        401 => '401 Unauthorized',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        500 => '500 Internal Server Error',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof HttpException) {
            $statusCode = in_array($e->getStatusCode(), array_keys(self::$errorMessages))
                ? $e->getStatusCode()
                : 500;

            return $this->response($statusCode);
        }
        if ($e instanceof ModelNotFoundException) {
            return $this->response(404);
        }
        if ($e instanceof PDOException) {
            return $this->response(500);
        }
        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $this->errorMessages[401]], 401);
        }
        return redirect()->guest(route('login'));
    }

    /**
     * Response
     *
     * @param  int $statusCode
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    private function response($statusCode)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'error' => self::$errorMessages[$statusCode] ?? 'Something went wrong, please try again later.'
            ], $statusCode);
        }
        return response()->view("errors.{$statusCode}", [], $statusCode);
    }
}
