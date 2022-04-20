<?php

namespace App\Services\Implementations\RabbitMQ;

use Exception;
use Throwable;
use Illuminate\Queue\Worker;
use Illuminate\Container\Container;
use Illuminate\Queue\WorkerOptions;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exception\AMQPRuntimeException;
use App\Services\Implementations\RabbitMQ\RabbitMQQueue;
use Illuminate\Contracts\Container\Container as ContainerContract;

class Consumer extends Worker
{
    /** @var Container */
    protected $container;

    /** @var string */
    protected $consumerTag;

    /** @var int */
    protected $prefetchSize;

    /** @var int */
    protected $maxPriority;

    /** @var int */
    protected $prefetchCount;

    /** @var AMQPChannel */
    protected $channel;

    /** @var object|null */
    protected $currentJob;

    /**
     * Set container
     *
     * @param  \Illuminate\Contracts\Container\Container $value
     * @return void
     */
    public function setContainer(ContainerContract $value)
    {
        $this->container = $value;
    }

    /**
     * Set consumer tag
     *
     * @param  string $value
     * @return void
     */
    public function setConsumerTag(string $value)
    {
        $this->consumerTag = $value;
    }

    /**
     * Set max priority
     *
     * @param  int $value
     * @return void
     */
    public function setMaxPriority(int $value)
    {
        $this->maxPriority = $value;
    }

    /**
     * Set prefetch size
     *
     * @param  int $value
     * @return void
     */
    public function setPrefetchSize(int $value)
    {
        $this->prefetchSize = $value;
    }

    /**
     * Set prefetch count
     *
     * @param  int $value
     * @return void
     */
    public function setPrefetchCount(int $value)
    {
        $this->prefetchCount = $value;
    }

    /**
     * Listen to the given queue in a loop.
     *
     * @param  string  $connectionName
     * @param  string  $queue
     * @param  WorkerOptions  $options
     * @return int
     *
     * @throws Throwable
     */
    public function daemon($connectionName, $queue, WorkerOptions $options)
    {
        if ($this->supportsAsyncSignals()) {
            $this->listenForSignals();
        }

        $lastRestart = $this->getTimestampOfLastQueueRestart();

        [$startTime, $jobsProcessed] = [hrtime(true) / 1e9, 0];

        /** @var RabbitMQQueue $connection */
        $connection = $this->manager->connection($connectionName);

        $this->channel = $connection->getChannel();

        $this->channel->basic_qos(
            $this->prefetchSize,
            $this->prefetchCount,
            null
        );

        $jobClass = $connection->getJobClass();
        $arguments = [];
        if ($this->maxPriority) {
            $arguments['priority'] = ['I', $this->maxPriority];
        }

        $this->channel->basic_consume(
            $queue,
            $this->consumerTag,
            false,
            false,
            false,
            false,
            function (AMQPMessage $message) use ($connection, $options, $connectionName, $queue, $jobClass, &$jobsProcessed) {
                $job = new $jobClass(
                    $this->container,
                    $connection,
                    $message,
                    $connectionName,
                    $queue
                );

                $this->currentJob = $job;

                if ($this->supportsAsyncSignals()) {
                    $this->registerTimeoutHandler($job, $options);
                }

                $jobsProcessed++;

                $this->runJob($job, $connectionName, $options);

                if ($this->supportsAsyncSignals()) {
                    $this->resetTimeoutHandler();
                }
            },
            null,
            $arguments
        );

        while ($this->channel->is_consuming()) {
            // Before reserving any jobs, we will make sure this queue is not paused and
            // if it is we will just pause this worker for a given amount of time and
            // make sure we do not need to kill this worker process off completely.
            if (! $this->daemonShouldRun($options, $connectionName, $queue)) {
                $this->pauseWorker($options, $lastRestart);

                continue;
            }

            // If the daemon should run (not in maintenance mode, etc.), then we can wait for a job.
            try {
                $this->channel->wait(null, true, (int) $options->timeout);
            } catch (AMQPRuntimeException $exception) {
                $this->exceptions->report($exception);

                $this->kill(1);
            } catch (Exception|Throwable $exception) {
                $this->exceptions->report($exception);

                $this->stopWorkerIfLostConnection($exception);
            }

            // If no job is got off the queue, we will need to sleep the worker.
            if ($this->currentJob === null) {
                $this->sleep($options->sleep);
            }

            // Finally, we will check to see if we have exceeded our memory limits or if
            // the queue should restart based on other indications. If so, we'll stop
            // this worker and let whatever is "monitoring" it restart the process.
            $status = $this->stopIfNecessary(
                $options,
                $lastRestart,
                $startTime,
                $jobsProcessed,
                $this->currentJob
            );

            if (! is_null($status)) {
                return $this->stop($status);
            }

            $this->currentJob = null;
        }
    }

    /**
     * Determine if the daemon should process on this iteration.
     *
     * @param  WorkerOptions  $options
     * @param  string  $connectionName
     * @param  string  $queue
     * @return bool
     */
    protected function daemonShouldRun(WorkerOptions $options, $connectionName, $queue)
    {
        return ! ((($this->isDownForMaintenance)() && ! $options->force) || $this->paused);
    }

    /**
     * Stop listening and bail out of the script.
     *
     * @param  int  $status
     * @return int
     */
    public function stop($status = 0)
    {
        // Tell the server you are going to stop consuming.
        // It will finish up the last message and not send you any more.
        $this->channel->basic_cancel($this->consumerTag, false, true);

        return parent::stop($status);
    }
}
