<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\BannedUsers;
use Carbon\Carbon;
use App\Console\Commands\Unblock;
use App\Jobs\DeleteBannedUsers;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DemoCron::class,
        Unblock::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        // $schedule->command('demo:cron')
        //         ->everyMinute();

        // $schedule->command('user:unblock')->everyMinute();

        $schedule->call(function () {
            BannedUsers::where('forever_ban', '!=', '1')->where('temporary_ban', '<=', Carbon::now())->delete();
        })->everyMinute()->runInBackground();
        //daily()
        //$schedule->job(new DeleteBannedUsers)->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
