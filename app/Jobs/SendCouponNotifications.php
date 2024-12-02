<?php

namespace App\Jobs;

use App\Notifications\NewCouponSent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCouponNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $coupon;

    public function __construct($coupon)
    {
        $this->coupon = $coupon;
    }

    public function handle()
    {
        $users = $this->coupon->users;

        foreach ($users as $user) {
            $user->notify(new NewCouponSent($this->coupon));
        }
    }
}
