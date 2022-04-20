<?php

namespace App\Services\Implementations\RabbitMQ;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Queue\Events\WorkerStopping;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPLazyConnection;
use Illuminate\Queue\Connectors\ConnectorInterface;
use App\Services\Implementations\RabbitMQ\RabbitMQQueue;
use App\Services\Implementations\RabbitMQ\Horizon\Listeners\RabbitMQFailedEvent;
use App\Services\Implementations\RabbitMQ\Horizon\RabbitMQQueue as HorizonRabbitMQQueue;

class RabbitMQConnector implements ConnectorInterface
{
    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    private $dispatcher;

    /**
     * __construct
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $dispatcher
     * @return void
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @implement ConnectorInterface
     *
     * Get custom queue instance
     *
     * @param  array $config
     * @return \App\Services\Implementations\RabbitMQ\RabbitMQQueue
     * @throws Exception
     */
    public function connect(array $config)
    {
        $connection = $this->createConnection(Arr::except($config, 'options.queue'));

        $queue = $this->createQueue(
            Arr::get($config, 'worker', 'default'),
            $connection,
            $config['queue'],
            Arr::get($config, 'options.queue', [])
        );

        if (! $queue instanceof RabbitMQQueue) {
            throw new InvalidArgumentException('Invalid worker.');
        }

        if ($queue instanceof HorizonRabbitMQQueue) {
            $this->dispatcher->listen(JobFailed::class, RabbitMQFailedEvent::class);
        }

        $this->dispatcher->listen(WorkerStopping::class, static function () use ($queue) {
            $queue->close();
        });

        return $queue;
    }

    /**
     * Create a connection
     *
     * @param  array  $config
     *
     * @return \PhpAmqpLib\Connection\AbstractConnection
     * @throws \Exception
     */
    protected function createConnection(array $config)
    {
        /**
         * @var AbstractConnection $connection
         */
        $connection = Arr::get($config, 'connection', AMQPLazyConnection::class);

        // disable heartbeat when not configured, so long-running tasks will not fail
        $config = Arr::add($config, 'options.heartbeat', 0);

        return $connection::create_connection(
            Arr::shuffle(Arr::get($config, 'hosts', [])),
            $this->filter(Arr::get($config, 'options', []))
        );
    }

    /**
     * Create a queue for the worker
     *
     * @param  string  $worker
     * @param  \PhpAmqpLib\Connection\AbstractConnection  $connection
     * @param  string  $queue
     * @param  array  $options
     * @return HorizonRabbitMQQueue|RabbitMQQueue|Queue
     */
    protected function createQueue(string $worker, AbstractConnection $connection, string $queue, array $options = [])
    {
        switch ($worker) {
            case 'default':
                return new RabbitMQQueue($connection, $queue, $options);
            case 'horizon':
                return new HorizonRabbitMQQueue($connection, $queue, $options);
            default:
                return new $worker($connection, $queue, $options);
        }
    }

    /**
     * Only get values not null.
     *
     * @param  array  $array
     * @return array
     */
    private function filter(array $array)
    {
        foreach ($array as $index => &$value) {
            if (is_array($value)) {
                $value = $this->filter($value);

                continue;
            }

            // If the value is null then remove it.
            if ($value === null) {
                unset($array[$index]);

                continue;
            }
        }

        return $array;
    }
}
