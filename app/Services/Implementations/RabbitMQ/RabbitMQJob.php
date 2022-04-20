<?php

namespace App\Services\Implementations\RabbitMQ;

use Illuminate\Support\Arr;
use Illuminate\Queue\Jobs\Job;
use PhpAmqpLib\Wire\AMQPTable;
use Illuminate\Container\Container;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Contracts\Queue\Job as JobContract;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;
use App\Services\Implementations\RabbitMQ\RabbitMQQueue;
use Illuminate\Contracts\Container\BindingResolutionException;
use App\Services\Implementations\RabbitMQ\Horizon\RabbitMQQueue as HorizonRabbitMQQueue;

class RabbitMQJob extends Job implements JobContract
{
    /**
     * The RabbitMQ queue instance.
     *
     * @var \App\Services\Implementations\RabbitMQ\RabbitMQQueue
     */
    protected $rabbitmq;

    /**
     * The RabbitMQ message instance.
     *
     * @var \PhpAmqpLib\Message\AMQPMessage
     */
    protected $message;

    /**
     * The JSON decoded version of "$message"
     *
     * @var array
     */
    protected $decoded;

    /**
     * The JSON decoded version of "$message"
     *
     * @param \Illuminate\Container\Container $container
     * @param \App\Services\Implementations\RabbitMQ\RabbitMQQueue $rabbitmq
     * @param \PhpAmqpLib\Message\AMQPMessage $message
     * @param string $connectionName
     * @param string $queue
     */
    public function __construct(
        Container $container,
        RabbitMQQueue $rabbitmq,
        AMQPMessage $message,
        string $connectionName,
        string $queue
    ) {
        $this->container = $container;
        $this->rabbitmq = $rabbitmq;
        $this->message = $message;
        $this->connectionName = $connectionName;
        $this->queue = $queue;
        $this->decoded = $this->payload();
    }

    /**
     * @implement Illuminate\Queue\Jobs\Job
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->decoded['id'] ?? null;
    }

    /**
     * @implement Illuminate\Queue\Jobs\Job
     *
     * Get the raw body of the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->message->getBody();
    }

    /**
     * Attempts
     *
     * @return int
     */
    public function attempts()
    {
        if (! $data = $this->getRabbitMQMessageHeaders()) {
            return 1;
        }

        $laravelAttempts = (int) Arr::get($data, 'laravel.attempts', 0);

        return $laravelAttempts + 1;
    }

    /**
     * Mark as failed
     *
     * @return void
     */
    public function markAsFailed()
    {
        parent::markAsFailed();

        // We must tel rabbitMQ this Job is failed
        // The message must be rejected when the Job marked as failed, in case rabbitMQ wants to do some extra magic.
        // like: Death lettering the message to an other exchange/routing-key.
        $this->rabbitmq->reject($this);
    }

    /**
     * Delete the job from the queue
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function delete()
    {
        parent::delete();

        // When delete is called and the Job was not failed, the message must be acknowledged.
        // This is because this is a controlled call by a developer. So the message was handled correct.
        if (! $this->failed) {
            $this->rabbitmq->ack($this);
        }

        // required for Laravel Horizon
        if ($this->rabbitmq instanceof HorizonRabbitMQQueue) {
            $this->rabbitmq->deleteReserved($this->queue, $this);
        }
    }

    /**
     * Release the job back into the queue.
     *
     * @param  int  $delay
     *
     * @throws AMQPProtocolChannelException
     */
    public function release($delay = 0)
    {
        parent::release();

        // Always create a new message when this Job is released
        $this->rabbitmq->laterRaw($delay, $this->message->getBody(), $this->queue, $this->attempts());

        // Releasing a Job means the message was failed to process.
        // Because this Job message is always recreated and pushed as new message, this Job message is correctly handled.
        // We must tell rabbitMQ this job message can be removed by acknowledging the message.
        $this->rabbitmq->ack($this);
    }

    /**
     * Get the underlying RabbitMQ connection.
     *
     * @return \App\Services\Implementations\RabbitMQ\RabbitMQQueue
     */
    public function getRabbitMQ()
    {
        return $this->rabbitmq;
    }

    /**
     * Get the underlying RabbitMQ message.
     *
     * @return \PhpAmqpLib\Message\AMQPMessage
     */
    public function getRabbitMQMessage()
    {
        return $this->message;
    }

    /**
     * Get the headers from the rabbitMQ message.
     *
     * @return array|null
     */
    protected function getRabbitMQMessageHeaders()
    {
        /**
         * @var AMQPTable|null $headers
         */
        if (!$headers = Arr::get($this->message->get_properties(), 'application_headers')) {
            return null;
        }

        return $headers->getNativeData();
    }
}
