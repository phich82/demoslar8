<?php

namespace App\Services\Implementations\RabbitMQ\Console;

use Illuminate\Console\Command;
use App\Services\Implementations\RabbitMQ\RabbitMQConnector;

class QueueBindCommand extends Command
{
    protected $signature = 'rabbitmq:queue-bind
                           {queue}
                           {exchange}
                           {connection=rabbitmq : The name of the queue connection to use}
                           {--routing-key= : Bind queue to exchange via routing key}';

    protected $description = 'Bind queue to exchange';

    /**
     * @param  RabbitMQConnector  $connector
     *
     * @throws Exception
     */
    public function handle(RabbitMQConnector $connector): void
    {
        $config = $this->laravel['config']->get('queue.connections.'.$this->argument('connection'));

        $queue = $connector->connect($config);

        $queue->bindQueue(
            $this->argument('queue'),
            $this->argument('exchange'),
            (string) $this->option('routing-key')
        );

        $this->info('Queue bound to exchange successfully.');
    }
}
