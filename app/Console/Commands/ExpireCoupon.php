<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Coupon;

class ExpireCoupon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:coupon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Faire expirer le coupon';

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
        // Code pour invalider le coupon
        Coupon::where('status', 'actif')->update(['status' => 'inactif']);
    }
}