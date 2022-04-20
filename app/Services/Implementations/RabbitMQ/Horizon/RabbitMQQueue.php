<?php

namespace App\Services\Implementations\RabbitMQ\Horizon;

use Exception;
// use Laravel\Horizon\Events\JobDeleted;
// use Laravel\Horizon\Events\JobPushed;
// use Laravel\Horizon\Events\JobReserved;
// use Laravel\Horizon\JobPayload;
use Illuminate\Support\Str;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;
use App\Services\Implementations\RabbitMQ\RabbitMQQueue as BaseRabbitMQQueue;
use App\Services\Implementations\RabbitMQ\Horizon\Traits\VerifyInstallHorizon;

class RabbitMQQueue extends BaseRabbitMQQueue
{
    use VerifyInstallHorizon;

    /**
     * The job that last pushed to queue via the "push" method.
     *
     * @var object|string
     */
    protected $lastPushed;

    /**
     * Get the number of queue jobs that are ready to process.
     *
     * @param  string|null  $queue
     * @return int
     *
     * @throws AMQPProtocolChannelException
     */
    public function readyNow($queue = null): int
    {
        return $this->size($queue);
    }

    /**
     * {@inheritdoc}
     */
    public function push($job, $data = '', $queue = null)
    {
        $this->lastPushed = $job;

        return parent::push($job, $data, $queue);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        $this->checkInstallHorizon();

        $payload = (new $this->JobPayload($payload))->prepare($this->lastPushed)->value;

        return tap(parent::pushRaw($payload, $queue, $options), function () use ($queue, $payload) {
            $this->event($this->getQueue($queue), new $this->JobPushed($payload));
        });
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        $this->checkInstallHorizon();

        $payload = (new $this->JobPayload($this->createPayload($job, $data)))->prepare($job)->value;

        return tap(parent::pushRaw($payload, $queue, ['delay' => $this->secondsUntil($delay)]), function () use ($payload, $queue) {
            $this->event($this->getQueue($queue), new $this->JobPushed($payload));
        });
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function pop($queue = null)
    {
        $this->checkInstallHorizon();

        return tap(parent::pop($queue), function ($result) use ($queue) {
            if (is_a($result, RabbitMQJob::class, true)) {
                $this->event($this->getQueue($queue), new $this->JobReserved($result->getRawBody()));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function release($delay, $job, $data, $queue, $attempts = 0)
    {
        $this->lastPushed = $job;

        return parent::release($delay, $job, $data, $queue, $attempts);
    }

    /**
     * Fire the job deleted event.
     *
     * @param  string  $queue
     * @param  RabbitMQJob  $job
     * @return void
     *
     * @throws BindingResolutionException|Exception
     */
    public function deleteReserved($queue, $job): void
    {
        $this->checkInstallHorizon();

        $this->event($this->getQueue($queue), new $this->JobDeleted($job, $job->getRawBody()));
    }

    /**
     * Fire the given event if a dispatcher is bound.
     *
     * @param  string  $queue
     * @param  mixed  $event
     * @return void
     *
     * @throws BindingResolutionException
     */
    protected function event($queue, $event): void
    {
        if ($this->container && $this->container->bound(Dispatcher::class)) {
            $this->container->make(Dispatcher::class)->dispatch(
                $event->connection($this->getConnectionName())->queue($queue)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRandomId(): string
    {
        return Str::uuid();
    }
}
