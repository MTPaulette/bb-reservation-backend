<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\NewCouponSent;
use App\Models\Coupon;

class SendCoupon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:coupon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer le coupon par email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Code pour envoyer le coupon par email
        $coupon = Coupon::latest()->first();
        if ($coupon && !$coupon->sent) {
            $users = $coupon->users;
    
            foreach ($users as $user) {
                $user->notify(new NewCouponSent($coupon));
            }
            $coupon->sent = true;
            $coupon->save();
        }
    }
}
