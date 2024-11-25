<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Reminder;
use App\Notifications\ReminderDue;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
        $reminders = Reminder::where('remind_at', '<=', now())->where('status', '<>', 2)->get();

        foreach ($reminders as $reminder) {
            $reminder->user->notify(new ReminderDue($reminder));
            $reminder->update(['status' => 2]);
        }
    })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
