<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use App\Services\Implementations\RabbitMQ\Consumer;
use App\Services\Implementations\RabbitMQ\RabbitMQ;
use App\Services\Implementations\RabbitMQ\RabbitMQConnector;
use App\Services\Implementations\RabbitMQ\Console\ConsumeCommand;
use App\Services\Implementations\RabbitMQ\Console\ExchangeDeclareCommand;

class RabbitMQServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register console commands for RabbitMQ
        if ($this->app->runningInConsole()) {
            $this->app->singleton('rabbitmq.consumer', function () {
                $isDownForMaintenance = function () {
                    return $this->app->isDownForMaintenance();
                };

                return new Consumer(
                    $this->app['queue'],
                    $this->app['events'],
                    $this->app[ExceptionHandler::class],
                    $isDownForMaintenance
                );
            });

            $this->app->singleton(ConsumeCommand::class, static function ($app) {
                return new ConsumeCommand(
                    $app['rabbitmq.consumer'],
                    $app['cache.store']
                );
            });

            $this->commands([
                // Console\ConsumeCommand::class,
                \App\Services\Implementations\RabbitMQ\Console\ConsumeCommand::class,
            ]);
        }

        $this->commands([
            // Console\ExchangeDeclareCommand::class,
            // Console\ExchangeDeleteCommand::class,
            // Console\QueueBindCommand::class,
            // Console\QueueDeclareCommand::class,
            // Console\QueueDeleteCommand::class,
            // Console\QueuePurgeCommand::class,
            \App\Services\Implementations\RabbitMQ\Console\ExchangeDeclareCommand::class,
            \App\Services\Implementations\RabbitMQ\Console\ExchangeDeleteCommand::class,
            \App\Services\Implementations\RabbitMQ\Console\QueueBindCommand::class,
            \App\Services\Implementations\RabbitMQ\Console\QueueDeclareCommand::class,
            \App\Services\Implementations\RabbitMQ\Console\QueueDeleteCommand::class,
            \App\Services\Implementations\RabbitMQ\Console\QueuePurgeCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Create a new driver (rabbitmq)
        /** @var QueueManager $queue */
        $queue = $this->app['queue'];

        $queue->addConnector('rabbitmq', function () {
            return new RabbitMQConnector($this->app['events']);
        });
    }
}
