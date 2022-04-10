<?php

use App\Models\Order;
use App\Events\LoginSubscriber;
use App\Events\LogoutSubscriber;
use App\Events\LocalNotification;
use App\Events\OrderNormalChannel;
use App\Events\ChatPresenceChannel;
use App\Events\OrderPrivateChannel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Events\LocalNotificationQueue;
use Illuminate\Support\Facades\Request;
use Illuminate\Cache\RateLimiting\Limit;
use App\Http\Middleware\VerifyPermission;
use Illuminate\Support\Facades\RateLimiter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
///////////////////////////////////////////////////////////////////

Route::get('/broadcast-normal', function () {
    // event(new OrderNormalChannel(1));
    OrderNormalChannel::dispatch(1);
    echo 'Order status updated.';
});

Route::get('/broadcast-private', function () {
    OrderPrivateChannel::dispatch(1);
    echo 'Order status (private channel) updated.';
});

Route::get('/broadcast-presence', function () {
    $message = new stdClass();
    $message->room_id = 1;
    ChatPresenceChannel::dispatch($message);
    echo 'Chat user joined.';
});

Route::get('/event', function () {
    $message = new stdClass();
    $message->room_id = 1;
    LocalNotification::dispatch($message);
    echo 'Local notification sent.';
});
Route::get('/event-queue', function () {
    $message = new stdClass();
    $message->room_id = 1;
    LocalNotificationQueue::dispatch($message);
    echo 'Local notification (queue) sent.';
});
Route::get('/event-subscribe', function () {
    $message = new stdClass();
    $message->room_id = 1;
    LoginSubscriber::dispatch($message);
    LogoutSubscriber::dispatch($message);
    echo 'Subscribed successfully.';
});

Route::get('/module-view', function () {
    dd(config('admin'));
    // dd(__('admin::auth.test'));
    return view('welcome');
    return view('admin::test');
});

// Route::controller(OrderController::class)->group(function () {
//     Route::get('/orders/{id}', 'show');
//     Route::post('/orders', 'store');
// });

Route::prefix('admin')->group(function () {
    Route::get('/users', function () {
        // Matches The "/admin/users" URL
    });
});

Route::name('admin.')->group(function () {
    Route::get('/users', function () {
        // Route assigned name "admin.users"...
    })->name('users');
});

// Route::middleware([VerifyPermission::class])->group(function () {
//     Route::get('/', function () {
//         //
//     });
//     // Ignore verifying permission
//     Route::get('/profile', function () {
//         //
//     })->withoutMiddleware([VerifyPermission::class]);
// });



// Define a throttle:
// We can limit access to the route to 100 times per minute per authenticated user ID
// or 10 times per minute per IP address for guests
RateLimiter::for('uploads', function (Request $request) {
    return [
        $request->user()
                ? Limit::perMinute(100)->by($request->user()->id)
                : Limit::perMinute(10)->by($request->ip())
    ];
});

// Attaching Rate Limiters To Routes
Route::middleware(['throttle:uploads'])->group(function () {
    Route::post('/audio', function () {
        //
    });
    Route::post('/video', function () {
        //
    });
});
