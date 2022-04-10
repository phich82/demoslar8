<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

abstract class Queue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * A particular queued listener should be dispatched after all open database transactions have been committed
     *
     * @var boolean
     */
    public $afterCommit = true;

    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'sync'; // sqs

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = null;

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 60;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        // Get queue name automatically
        if (!$this->queue) {
            $class = explode('\\', get_class($this));
            $this->queue = end($class);
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Illuminate\Events\Dispatcher  $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed($event, $exception)
    {
        dd('failed queue', $exception);
    }
}
