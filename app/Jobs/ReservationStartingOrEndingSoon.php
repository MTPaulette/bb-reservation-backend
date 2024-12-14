<?php

namespace App\Jobs;

use App\Helpers\User as HelpersUser;
use App\Models\Reservation;
use App\Notifications\ReservationEndingSoon;
use App\Notifications\ReservationStartingSoon;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReservationStartingOrEndingSoon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle()
    {
        $now = Carbon::now();
        $now_30_min = $now->copy()->addMinutes(30)->format("H:i");
        $today = $now->copy()->format('Y-m-d');

        // Récupérer les réservations qui commencent dans 30 minutes exactement
        $reservationsStartingSoon =
            Reservation::where(function ($query) use ($today) {
                $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
            })
            ->where('start_hour', $now_30_min)
            ->get();

        // Envoyer des notifications aux utilisateurs de ces réservations
        foreach ($reservationsStartingSoon as $reservation) {
            $reservation->client->notify(new ReservationStartingSoon($reservation));
            $superadmin_admins = HelpersUser::getSuperadminAndAdmins($reservation->ressource->agency_id);
            foreach($superadmin_admins as $admin) {
                $admin->notify(new ReservationStartingSoon($reservation));
            }
        }

        // Récupérer les réservations qui commencent dans 30 minutes exactement
        $reservationsEndingSoon =
            Reservation::where(function ($query) use ($today) {
                $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
            })
            ->where('end_hour', $now_30_min)
            ->get();

        // Envoyer des notifications aux utilisateurs de ces réservations
        foreach ($reservationsEndingSoon as $reservation) {
            $reservation->client->notify(new ReservationEndingSoon($reservation));
            $superadmin_admins = HelpersUser::getSuperadminAndAdmins($reservation->ressource->agency_id);
            foreach($superadmin_admins as $admin) {
                $admin->notify(new ReservationEndingSoon($reservation));
            }
        }
    }
}

/*
        // Récupérer les réservations qui finissent dans 30 minutes exactement
        $reservationsEndingSoon = Reservation::where('end_at', $now->addMinutes(30)->toDateTimeString())->get();


        foreach ($reservationsEndingSoon as $reservation) {
            $reservation->user->notify(new ReservationEndingSoon($reservation, 'Votre réservation va se terminer dans 30 minutes.'));
        }
            */