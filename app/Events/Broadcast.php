<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithBroadcasting;

abstract class Broadcast
{
    use InteractsWithBroadcasting, SerializesModels;

    /**
     * Commit after queue
     *
     * @var boolean
     */
    public $afterCommit = true;

    protected $broadcastQueue = 'default';
    protected $broadcastName = null;
    protected $broadcastDriver = null;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        // Set broadcast connection
        if (!$this->broadcastDriver) {
            $this->broadcastDriver = env('BROADCAST_DRIVER');
        }
        $this->broadcastVia($this->broadcastDriver);
    }

    /**
     * The event's broadcast name.
     *
     * @important [
     *  If using broadcastAs method, at client, add '.' mark right before event name.
     *  This will instruct Echo to not prepend the application's namespace to the event.
     * ]
     *
     * @return string
     */
    public function broadcastAs()
    {
        if (!$this->broadcastName) {
            $class = explode('\\', get_class($this));
            $this->broadcastName = end($class);
        }
        return $this->broadcastName;
    }

    /**
     * The name of the queue on which to place the broadcasting job.
     *
     * @return string
     */
    public function broadcastQueue()
    {
        return $this->broadcastQueue;
    }
}
