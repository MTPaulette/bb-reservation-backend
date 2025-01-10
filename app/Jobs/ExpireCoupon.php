<?php

namespace App\Jobs;

use App\Models\Coupon;
use App\Notifications\CouponExpired;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpireCoupon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle()
    {
        $now = Carbon::now()->format("Y-m-d H:i");
        $coupons = Coupon::where("expired_on", "<=", $now)->where("status", "!=", "expired")->get();
        foreach($coupons as $coupon) {
            $users = $coupon->users;

            foreach ($users as $user) {
                $user->notify(new CouponExpired($coupon));
            }
            $coupon->status = "expired";
            $coupon->save();
        }
    }
}