<?php

namespace App\Listeners;

use App\Events\LoginSubscriber;
use App\Events\LogoutSubscriber;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle user login events.
     */
    public function handleUserLogin($event)
    {
        var_dump('handleUserLogin', $event);
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout($event)
    {
        var_dump('handleUserLogout', $event);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(LoginSubscriber::class, [UserSubscriber::class, 'handleUserLogin']);
        $events->listen(LogoutSubscriber::class, [UserSubscriber::class, 'handleUserLogout']);
    }
}
