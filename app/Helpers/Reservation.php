<?php

namespace App\Helpers;

use App\Models\Reservation as ModelsReservation;

class Reservation
{
    public static function getState($initial_amount, $amount_due)
    {
        $state = 'pending';
        if($amount_due == 0 ){
            $state = 'totally paid';
        }
        $half_amount = $initial_amount/2;
        if(
            $amount_due < $initial_amount &&
            $amount_due > $half_amount
        ){
            $state = 'partially paid';
        }
        if(
            $amount_due <= $half_amount &&
            $amount_due > 0
        ){
            $state = 'confirmed';
        }
        if($amount_due == $initial_amount ){
            $state = 'pending';
        }
        return $state;
    }

    public static function isAvailable(
        $ressource,
        $start_date_confirmed, $end_date_confirmed,
        $start_hour_confirmed, $end_hour_confirmed
    ) {
        //on verifie la ressource est disponible ce jour a cette heure
        $reservations_of_ressource =
            ModelsReservation::where('ressource_id', $ressource->id)
            ->where(function ($query) use ($start_date_confirmed, $end_date_confirmed) {
                $query->where(function ($query) use ($start_date_confirmed, $end_date_confirmed) {
                    $query->where('start_date', '>=', $start_date_confirmed)
                        ->where('start_date', '<', $end_date_confirmed);
                })
                ->orWhere(function ($query) use ($start_date_confirmed, $end_date_confirmed) {
                    $query->where('end_date', '>', $start_date_confirmed)
                        ->where('end_date', '=<', $end_date_confirmed);
                })
                ->orWhere(function ($query) use ($start_date_confirmed, $end_date_confirmed) {
                    $query->where('start_date', '<=', $start_date_confirmed)
                        ->where('end_date', '>=', $end_date_confirmed);
                });
            })
            ->where(function ($query) use ($start_hour_confirmed, $end_hour_confirmed) {
                $query->where(function ($query) use ($start_hour_confirmed, $end_hour_confirmed) {
                    $query->where('start_hour', '>=', $start_hour_confirmed)
                        ->where('start_hour', '<', $end_hour_confirmed);
                })
                ->orWhere(function ($query) use ($start_hour_confirmed, $end_hour_confirmed) {
                    $query->where('end_hour', '>', $start_hour_confirmed)
                        ->where('end_hour', '=<', $end_hour_confirmed);
                })
                ->orWhere(function ($query) use ($start_hour_confirmed, $end_hour_confirmed) {
                    $query->where('start_hour', '<=', $start_hour_confirmed)
                        ->where('end_hour', '>=', $end_hour_confirmed);
                });
            })
            ->where(function ($query) {
                $query->where('state', 'confirmed')
                    ->orWhere('state', 'totally paid');
            })
            ->count();

        if($reservations_of_ressource >= $ressource->quantity){
            return false;
        }
        return true;
    }
}