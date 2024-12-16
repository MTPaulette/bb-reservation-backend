<?php

namespace App\Jobs;

use App\Models\Reservation_draft;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClearReservation_draftTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle()
    {
        $reservation_drafts = Reservation_draft::get();
        foreach($reservation_drafts as $reservation_draft) {
            $reservation_draft->delete();
        }
    }
}