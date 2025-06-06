<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\User;
use App\Models\Objet;
use App\Notifications\AdminDailyReport;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
   /* protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }*/

    protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        $admin = User::where('is_admin', true)->first();
        $stats = [
            'perdus' => Objet::perdus()->whereDate('created_at', today())->count(),
            'trouves' => Objet::trouves()->whereDate('created_at', today())->count()
        ];
        
        $admin->notify(new AdminDailyReport($stats, Objet::latest()->first()));
    })->dailyAt('18:00');

     $schedule->command('model:prune', [
        '--model' => [Message::class, MessageReadStatus::class, MessageReaction::class],
    ])->daily();

    $schedule->command('clean:orphaned-attachments')->daily();
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
