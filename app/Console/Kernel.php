<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SavingCycleDailyCharge::class,
        Commands\SavingCycleWeeklyCharge::class,
        Commands\SavingCycleMonthlyCharge::class,
        Commands\TestCron::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('test-cron:email')->everyMinute();
        $schedule->command('saving-cycle:daily')->hourly();
        $schedule->command('saving-cycle:weekly')->hourly();
        $schedule->command('saving-cycle:monthly')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
