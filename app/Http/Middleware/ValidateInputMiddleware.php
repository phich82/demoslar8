<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Validation\Validator;

class ValidateInputMiddleware
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::autoValidate();
        if ($validator !== true) {
            if (isApi()) {
                return $this->responseError($validator->errors());
            }
            if ($request->ajax()) {
                return $this->responseError($validator->errors(), 422);
            }
            //return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        return $next($request);
    }
}
