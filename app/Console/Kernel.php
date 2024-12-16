<?php

namespace App\Console;

use App\Jobs\ClearReservation_draftTable;
use App\Jobs\ExpireCoupon;
use App\Jobs\ReservationStartingOrEndingSoon;
use App\Jobs\SendCoupon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new SendCoupon())->everyMinute(); //dailyAt('08:00');
        $schedule->job(new ExpireCoupon())->everyMinute(); //dailyAt('00:00');
        $schedule->job(new ReservationStartingOrEndingSoon())->everyMinute(); //everyMinute();
        $schedule->job(new ClearReservation_draftTable())->dailyAt('23:00');
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
