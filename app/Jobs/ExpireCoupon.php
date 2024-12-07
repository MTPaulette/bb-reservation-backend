<?php

namespace App\Jobs;

use App\Models\Coupon;
use App\Notifications\NewCouponSent;
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
        $coupons = Coupon::where("expired_on", "<=", Carbon::now())->get();
        foreach($coupons as $coupon) {
            $coupon->status = "expired";
            $coupon->save();
        }
    }
}