<?php

use App\Models\User;
use App\Models\Order;
use App\Mail\NewOrder;
use App\Services\Core;
use App\Mail\OrderShipped;
use App\Services\DBService;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Str;
use App\Services\AuthService;
use App\Services\UserService;
use App\Events\LoginSubscriber;
use App\Events\LogoutSubscriber;
use App\Events\LocalNotification;
use App\Events\OrderNormalChannel;
use App\Events\ChatPresenceChannel;
// use App\Services\HttpService;
use App\Events\OrderPrivateChannel;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Services\Facades\FileService;
use App\Services\Facades\HttpService;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;
use App\Events\LocalNotificationQueue;
use Illuminate\Support\Facades\Request;
use Illuminate\Cache\RateLimiting\Limit;
use App\Http\Middleware\VerifyPermission;
use App\Jobs\TestRabbitMQJob;
use App\Services\Facades\Mailer as FacadesMailer;
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

Route::get('/file', function () {
    // FileService::save('test.txt', 'Hello world.');
    // FileService::copy('test.txt', 'test/newtest.txt');
    // return FileService::download('test.txt', 'download.txt');
    echo 'Save file successfully.';
});

Route::get('/http', function () {
    dd(
        HttpService::get('todos/1'),
        HttpService::get('xxxxxx'),
        HttpService::withHttp(Http::google())->get('users'),
    );
});

Route::get('/sendmail', function () {
    // Only send mail with authorized emails with mailgun (on app.mailgun.com, add more authorized emails)
    // Mail::raw('Hello you', function ($message) {
    //     $message->to('jhphich82@gmail.com');
    //     $message->subject('Test mailgun.');
    // });
    // dd(config('services.mailgun'));
    $order = Order::findOrFail(1);
    // Preview email template before sending mail
    // return (new NewOrder($order))->render();
    // return (new OrderShipped($order))->render();
    // Mail::to('jhphich82@gmail.com')->send(new OrderShipped($order));
    FacadesMailer::send('jhphich82@gmail.com', new OrderShipped($order));
    dd('Sent.');
});

Route::get('/pagination', function (UserService $userService) {
    // dd(UserRepository::getTable(), RoleRepository::getTable());
    $sql = "
        --SELECT
        --    u.*
        --    ,o.items
        FROM users AS u
        LEFT JOIN orders AS o ON (u.id = o.user_id)
        WHERE
            u.email LIKE :email
    ";
    $sql2 = "
        SELECT
            u.*
            ,o.items
        FROM users AS u
        LEFT JOIN orders AS o ON (u.id = o.user_id)
        WHERE
            u.email LIKE :email
    ";
    $sql3 = "
        SELECT
            u.*
            ,o.items
        FROM users AS u
        LEFT JOIN orders AS o ON (u.id = o.user_id)
        --WHERE
            --u.email LIKE :email
    ";
    $prepareBinding = [
        'email' => '%e_'
    ];
    // dd(
    //     DBService::getPaginationTotal(['', $sql], $prepareBinding),
    //     // DBService::select($sql2, $prepareBinding),
    //     $userService->getBy([
    //         'id' => 9000
    //     ])->toArray(),
    //     $userService->getOne(1),
    //     DBService::paginate($sql3)->total()
    // );

    return view('test', ['pagination' => DBService::paginate($sql3)]);
});

Route::get('/permission', function (User $user) {
    dd($user->isAdmin());
    dd(
        Core::auth(),
        AuthService::auth(),
    );
});

Route::get('versioning', function () {
    // Get basic information from config file
    $config = read('app.Config.version::api');// config('version.api');
    if (empty($config)) {
        // throw new Exception("Missing `config/version.php` file or `api` key.");
        throw new Exception("Missing `app/Config/version.php` file or `api` key.");
    }
    $middleware = $config['middleware'] ?? 'api';
    $versionPattern = $config['pattern'] ?? '^V\d+$';

    // Get api versions from scaning api directory
    $versions = array_reduce(scandir($config['root_path']) ?: [], function ($carry, $name) use ($versionPattern) {
        if (preg_match("#{$versionPattern}#", $name)) {
            $carry[] = $name;
        }
        return $carry;
    }, []);

    // Setup routes for APIs by versions
    foreach ($versions as $version) {
        $version = trim($version);
        $prefix = trim($config['prefix'] ?? 'api', '/\\')."/".strtolower($version);
        $namespace = $config['controller_namespace'] ?? "App\\Api\\{$version}\\Controllers";
        $routePath = $config['route_path'] ?? base_path("routes".DIRECTORY_SEPARATOR."api_".strtolower($version).".php"); // routes/api_v1.php

        if (is_callable($namespace)) {
            $namespace = $namespace($version);
        }

        if (is_callable($routePath)) {
            $routePath = $routePath($version);
        }

        $out['prefix'] = $prefix;
        $out['namespace'] = $namespace;
        $out['route_path'] = $routePath;
    }
    dd($out);
});

Route::get('core', function () {
    Core::session()->set('test', 'Testing');
    $encryptedStr = Core::encrypter()->encrypt(['test' => 1, 'last_time' => date('Y-m-d H:i:s')]);
    dd(
        Core::session()->get('test'),
        // If the route is full path, we should baseUrl() method for reseting baseUrl of Http
        Core::http()->baseUrl()->get('https://jsonplaceholder.typicode.com/users'),
        Core::http()->get('/users/5'),
        $encryptedStr,
        Core::encrypter()->decrypt($encryptedStr)
    );
});

Route::get('/pagination', function (UserService $userService) {
    // dd(UserRepository::getTable(), RoleRepository::getTable());
    $sql = "
        --SELECT
        --    u.*
        --    ,o.items
        FROM users AS u
        LEFT JOIN orders AS o ON (u.id = o.user_id)
        WHERE
            u.email LIKE :email
    ";
    $sql2 = "
        SELECT
            u.*
            ,o.items
        FROM users AS u
        LEFT JOIN orders AS o ON (u.id = o.user_id)
        WHERE
            u.email LIKE :email
    ";
    $sql3 = "
        SELECT
            u.*
            ,o.items
        FROM users AS u
        LEFT JOIN orders AS o ON (u.id = o.user_id)
        --WHERE
            --u.email LIKE :email
    ";
    $prepareBinding = [
        'email' => '%e_'
    ];
    // dd(
    //     DBService::getPaginationTotal(['', $sql], $prepareBinding),
    //     // DBService::select($sql2, $prepareBinding),
    //     $userService->getBy([
    //         'id' => 9000
    //     ])->toArray(),
    //     $userService->getOne(1),
    //     DBService::paginate($sql3)->total()
    // );

    return view('test', ['pagination' => DBService::paginate($sql3)]);
});

Route::get('/queue', function () {
    // Queue::purge();
    // $queue = app()->make('queue');
    // $connection = $queue->connection('rabbitmq');

    // Queue::pushRaw($payload = Str::random());
    // Queue::push(new TestRabbitMQJob());
    // $job = Queue::pop();
    TestRabbitMQJob::dispatch([
        'processor' => 'App\\Services\\PushNotificationService'
    ]);

    echo 'Sent';

    dd(
        // $connection,
        // $connection->getConnection(),
        // $connection->getConnection()->isConnected(),
        // $connection->getChannel()->is_open()
        // Queue::size(),
        // $job->attempts(),
        // $job->payload(),
        // $job->getJobId(),
        // $job->delete(),
        // Queue::size(),
    );
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
