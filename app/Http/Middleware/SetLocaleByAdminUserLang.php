<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetLocaleByAdminUserLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = Constant::GUARD_ADMIN)
    {
        $currentLocale = app()->getLocale();

        if (Auth::guard($guard)->check()) {
            $currentLocale = Auth::guard($guard)->user()->locale_code;
        }

        app()->setLocale($currentLocale);

        return $next($request);
    }
}
