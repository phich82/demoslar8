<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class RateLimiterService
{
    public static function register()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(1000)->response(function () {
                return response('Custom response...', 429);
            });
        });

        // Allow users to access a given route 100 times per minute per IP address
        RateLimiter::for('uploads', function (Request $request) {
            return $request->user()->vipCustomer()
                        ? Limit::none()
                        : Limit::perMinute(100)->by($request->ip());
        });

        // limit access to the route to 100 times per minute per authenticated user ID
        // or 10 times per minute per IP address for guests
        RateLimiter::for('uploads', function (Request $request) {
            return $request->user()
                        ? Limit::perMinute(100)->by($request->user()->id)
                        : Limit::perMinute(10)->by($request->ip());
        });

        // Multiple Rate Limits
        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(500),
                Limit::perMinute(3)->by($request->input('email')),
            ];
        });

        // Attaching Rate Limiters To Routes
        // Route::middleware(['throttle:uploads'])->group(function () {
        //     Route::post('/audio', function () {
        //         //
        //     });

        //     Route::post('/video', function () {
        //         //
        //     });
        // });

        // Return false when the callback has no remaining attempts available
        // Otherwise, the attempt method will return the callback's result or true
        // RateLimiter::attempt('send-message:'.$user->id, $perMinute = 5, function () {});
        // Exceeded its maximum number of allowed attempts per minute
        // RateLimiter::tooManyAttempts('send-message:'.$user->id, $perMinute = 5);
        // Retrieve the number of attempts remaining for a given key
        // $attempsRemaining = RateLimiter::remaining('send-message:'.$user->id, $perMinute = 5);
        // Increment the number of total attempts
        // RateLimiter::hit('send-message:'.$user->id);
        // if (RateLimiter::remaining('send-message:'.$user->id, $perMinute = 5)) {
        //     RateLimiter::hit('send-message:'.$user->id);
        //     // Send message...
        // }

        // Determining Limiter Availability
        // if (RateLimiter::tooManyAttempts('send-message:'.$user->id, $perMinute = 5)) {
        //     $seconds = RateLimiter::availableIn('send-message:'.$user->id);
        //     return 'You may try again in '.$seconds.' seconds.';
        // }

        // Clearing Attempts
        // RateLimiter::clear('send-message:'.$message->user_id);
    }
}
