<?php

namespace App\Console;

use App\Jobs\SendCouponNotifications;
use App\Models\Coupon;
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
        // $schedule->command('inspire')->hourly();
        $coupon = Coupon::latest()->first();
        if ($coupon && !$coupon->sent) {
            $schedule->job(new SendCouponNotifications($coupon))->everyMinute();
            $coupon->sent = true;
            $coupon->save();
        }
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
