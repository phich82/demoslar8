<?php

namespace App\Listeners;

use App\Events\LocalNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLocalNotification
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
     * @param  \App\Events\LocalNotification  $event
     * @return void
     */
    public function handle(LocalNotification $event)
    {
        dd($event);
    }
}
