<?php

namespace App\Jobs;

use App\Models\Coupon;
use App\Notifications\NewCouponSent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCoupon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle()
    {
        //$coupon = Coupon::latest()->first();
        $coupons = Coupon::where("sent", false)->get();
        foreach($coupons as $coupon) {
            $users = $coupon->users;

            foreach ($users as $user) {
                $user->notify(new NewCouponSent($coupon));
            }
            $coupon->sent = true;
            $coupon->save();
        }
    }
}