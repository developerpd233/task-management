<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\TaskNotification;
use App\Notifications\TaskReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TaskReminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task-reminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->subDays(1)->toDateString();
        info('Task Reminder Cron Job Starts now on date '.$date);
        $tasks = Task::where('status','in progress')
        ->where( 'updated_at', '<=', $date)
        ->get();
        foreach ($tasks as $task){
            if($task->taskNotifications->where( 'sent_date', '<=', $date)->isEmpty()){
                $user = $task->user;
                $user->notify(new TaskReminder($task));
                TaskNotification::create([
                    'task_id' => $task->id,
                    'sent_date' => $date
                ]);
                info('Task Reminder Email Sent to '.$user->email);
            } else {
                info('Already notified for task'.$task->title);
            }
        }
        info('Task Reminder Cron Job Ends now.');
    }
}
