<?php

namespace App\Services;

use App\Console\Commands\SendMailCommand;
use Illuminate\Console\Scheduling\Schedule;

/**
 * Run schedules by command: `php artisan schedule:work`
 */
class ScheduleService
{
    /**
     * Register hook events for scheduled tasks
     *
     * @var array
     */
    const SCHEDULE_TASK_HOOKS = [
        \Illuminate\Console\Events\ScheduledTaskStarting::class => [
            \App\Listeners\LogScheduled\LogScheduledTaskStarting::class,
        ],

        \Illuminate\Console\Events\ScheduledTaskFinished::class => [
            \App\Listeners\LogScheduled\LogScheduledTaskFinished::class,
        ],

        \Illuminate\Console\Events\ScheduledBackgroundTaskFinished::class => [
            \App\Listeners\LogScheduled\LogScheduledBackgroundTaskFinished::class,
        ],

        \Illuminate\Console\Events\ScheduledTaskSkipped::class => [
            \App\Listeners\LogScheduled\LogScheduledTaskSkipped::class,
        ],

        \Illuminate\Console\Events\ScheduledTaskFailed::class => [
            \App\Listeners\LogScheduled\LogScheduledTaskFailed::class,
        ],
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public static function register(Schedule $schedule)
    {
        $schedule
            ->command('emails:send phich82@gmail.com')
            ->everyMinute()
            ->runInBackground()
            // scheduled tasks will NOT be run even if the previous instance of the task is still running
            ->withoutOverlapping()
            // Only execute on a single server
            ->onOneServer()
            // Hooks
            ->before(function () {
                // The task is about to execute...
            })
            ->after(function () {
                // The task has executed...
            })
            ->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            })
            ;
        // $schedule->command(SendMailCommand::class, ['phich82@gmail.com'])->everyMinute();

        // Scheduling Queued Jobs
        // $schedule->job(new Heartbeat)->everyMinute();

        // Scheduling Shell Commands
        // $schedule->exec('node /home/forge/script.js')->everyMinute();
    }
}
