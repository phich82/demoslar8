<?php
namespace App\Jobs;

abstract class SqsJob
{
    /**
     * Queue connection
     *
     * @var string|null
     */
    protected $queue_connection = 'sqs';
    /**
     * Queue connection name in environment
     *
     * @var string|null
     */
    protected $queue_connection_env = 'SQS_QUEUE_CONNECTION';
    /**
     * Queue name
     *
     * @var string|null
     */
    protected $queue_name = null;
    /**
     * Queue name in environment
     *
     * @var string|null
     */
    protected $queue_name_env = 'SQS_QUEUE';
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    protected $tries = 3;
    /**
     * Input data
     *
     * @var array|object
     */
    protected $data;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        /**
         * @override
         *
         * The name of the connection the job should be sent to.
         */
        $this->connection = env($this->queue_connection_env, $this->queue_connection);
        /**
         * @override
         *
         * The name of the queue the job should be sent to.
         */
        $this->queue = env($this->queue_name_env, $this->queue_name);
    }
}
