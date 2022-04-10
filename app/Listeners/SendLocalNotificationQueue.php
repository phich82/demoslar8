<?php

namespace App\Listeners;

use App\Events\LocalNotificationQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLocalNotificationQueue implements ShouldQueue
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
     * Handle the event.
     *
     * @param  \App\Events\LocalNotificationQueue  $event
     * @return void
     */
    public function handle(LocalNotificationQueue $event)
    {
        dd('queue', $event);
    }
}
