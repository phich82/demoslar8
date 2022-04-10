<?php

namespace App\Events;

use App\Models\Order;
use App\Events\Broadcast;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use stdClass;

class ChatPresenceChannel extends Broadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The order instance.
     *
     * @var \stdClass
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param  \stdClass  $message
     * @return void
     */
    public function __construct($message)
    {
        parent::__construct();

        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('chat.'.$this->message->room_id);
    }
}
