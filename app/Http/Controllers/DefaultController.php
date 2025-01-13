<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Ressource;
use App\Models\Reservation;

class DefaultController extends Controller
{
    public function getRessources()
    {
        $ressources =
            Ressource::get()
            ->map(function ($ressource) {
                return [
                    'id' => $ressource->id,
                    'agency' => $ressource->agency->name,
                    'nb_place' => $ressource->space->nb_place,
                    'price_hour' => $ressource->price_hour,
                    'price_midday' => $ressource->price_midday,
                    'price_day' => $ressource->price_day,
                    'price_week' => $ressource->price_week,
                    'price_month' => $ressource->price_month,
                    'space' => $ressource->space->name,
                    'quantity' => $ressource->quantity,
                    'image' => sizeof($ressource->space->images) != 0 ? $ressource->space->images[0]->src : null,
                    'characteristics' => 
                        $ressource->space->characteristics->map(function ($characteristic) {
                            return [
                                'name_en' => $characteristic->name_en,
                                'name_fr' => $characteristic->name_fr,
                            ];
                        }),
                ];
            })
            ->toArray();
        return response()->json($ressources, 201);
    }

    public function getCalendar() {
        $reservations = 
            Reservation::where('state', '!=', 'cancelled')
            ->get()
            ->map(function ($reservation) {
                return [
                    'title' => $reservation->ressource->space->name,
                    'agency' => $reservation->ressource->agency->name,
                    'state' => $reservation->state,
                    'start' => $reservation->start_date == $reservation->end_date ? $reservation->start_date . ' ' . $reservation->start_hour : $reservation->start_date,
                    'end' => $reservation->start_date == $reservation->end_date ? $reservation->end_date . ' ' . $reservation->end_hour : $reservation->end_date,
                ];
            })
            ->toArray();
        return response()->json($reservations, 201);
    }
    
    public function getReservations() {}

    public function getAgencies() {
        return Agency::orderBy("name")->get(['id', 'name']);
    }
}