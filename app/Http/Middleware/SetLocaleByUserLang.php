<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleByUserLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale($this->_getLocale());

        return $next($request);
    }

    private function _getLocale()
    {
        // Get code from header if found
        $code = request()->header('locale') ?: (request()->get('locale') ?: app()->getLocale());
        // Check exist code on locales table
        $locale = null;
        //$locale = <get locale by code here>
        return empty($locale) ? app()->getLocale() : $locale->code;
    }
}
