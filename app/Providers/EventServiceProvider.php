<?php

namespace App\Providers;

use Throwable;
use App\Events\LocalNotification;
use App\Listeners\UserSubscriber;
use Illuminate\Support\Facades\Event;
use App\Events\LocalNotificationQueue;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendLocalNotification;
use function Illuminate\Events\queueable;
use App\Listeners\SendLocalNotificationQueue;
use App\Services\ScheduleService;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ] + ScheduleService::SCHEDULE_TASK_HOOKS;

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        UserSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Registering Events
        Event::listen(LocalNotification::class, [SendLocalNotification::class, 'handle']);
        Event::listen(LocalNotificationQueue::class, [SendLocalNotificationQueue::class, 'handle']);

        // Event::listen(queueable(function (LocalNotification $event) {
        //     //
        // })->catch(function (LocalNotification $event, Throwable $e) {
        //     // The queued listener failed...
        // }));
        // Event::listen(queueable(function (LocalNotification $event) {
        //     //
        // })->onConnection('redis')->onQueue('podcasts')->delay(now()->addSeconds(10)));
        // Event::listen('event.*', function ($eventName, array $data) {
        //     //
        // });
    }
}
