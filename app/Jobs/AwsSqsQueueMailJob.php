<?php

namespace App\Jobs;

use App\Jobs\SqsJob;
// use App\Services\Facades\AppLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AwsSqsQueueMailJob extends SqsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $queue_connection_env = 'SQS_QUEUE_CONNECTION_MAIL';
    protected $queue_name_env = 'SQS_QUEUE_MAIL';

    /**
     * Create a new job instance.
     *
     * @param mixed $data
     * @return void
     */
    public function __construct($data = [])
    {
        parent::__construct();

        $this->data = $data;

        // AppLog::mail()->info(__CLASS__."::".__FUNCTION__." [SQS] => Connection: [{$this->connection}] - Queue: [{$this->queue}]");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!isset($this->data['processor'])) {
            // AppLog::mail()->error(__CLASS__."::".__FUNCTION__.' [SendMail][Data] => Missing "processor" key.');
            return;
        }
        $processor = app()->make($this->data['processor']);
        if (empty($processor)) {
            // AppLog::mail()->error(__CLASS__."::".__FUNCTION__." [SendMail][Data][processor] => Could not resolve [{$this->data['processor']}] class.");
            return;
        }
        // AppLog::mail()->info(__CLASS__."::".__FUNCTION__." [SendMail][Data][processor] => {$this->data['processor']}");
        $processor->send($this->data['data']);
    }
}
