<?php

namespace App\Jobs;

use App\Jobs\SqsJob;
// use App\Services\Facades\AppLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AwsSqsQueueJob extends SqsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

        // AppLog::push()->info(__CLASS__."::".__FUNCTION__." [SQS] => Connection: [{$this->connection}] - Queue: [{$this->queue}]");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!isset($this->data['processor'])) {
            // AppLog::push()->error(__CLASS__."::".__FUNCTION__.' [Push Notification][Data] => Missing "processor" key.');
            return;
        }
        $processor = app()->make($this->data['processor']);
        if (empty($processor)) {
            // AppLog::push()->error(__CLASS__."::".__FUNCTION__." [Push Notification][Data][processor] => Could not resolve [{$this->data['processor']}] class.");
            return;
        }
        // AppLog::push()->info(__CLASS__."::".__FUNCTION__." [Push Notification][Data][processor2] => {$this->data['processor']}");
        $processor->push($this->data['notification_data'], $this->data['target_app']);
    }
}
