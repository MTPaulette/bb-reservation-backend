<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Openingday;
use App\Models\Reservation;
use App\Models\Ressource;
use App\Models\Space;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ReservationController extends Controller
{
    public function reservationAllInformations()
    {
        return
        Reservation::with([
            'client' => function($query) {
                $query->select('id', 'lastname', 'firstname');
            },
            'coupon' => function($query) {
                $query->select('id', 'name', 'code');
            },
            'payments' => function($query) {
                $query->select('id', 'amount', 'bill_number');
            },
            'createdBy' => function($query) {
                $query->select('id', 'lastname', 'firstname');
            },
            'cancelledBy' => function($query) {
                $query->select('id', 'lastname', 'firstname');
            },
            'ressource' => [
                'space' => function($query) {
                    $query->select('id', 'name');
                },
                'agency' => function($query) {
                    $query->select('id', 'name');
                },
            ]
        ]);
    }

    public function index(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('manage_reservations') ||
            $authUser->hasPermission('show_all_reservation')
        ) {
            $reservations = $this->reservationAllInformations()->get();
            return response()->json($reservations, 201);
        }
        if($authUser->hasPermission('show_all_reservation_of_agency')) {
            $reservation_array = [];
            $reservations = $this->reservationAllInformations()
                                ->get()
                                ->where('ressource.agency_id', $authUser->work_at);
            foreach ($reservations as $reservation) {
                array_push($reservation_array, $reservation);
            }
            return response()->json($reservation_array, 201);
        }

        abort(403);
    }

    public function show(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('manage_reservations') ||
            $authUser->hasPermission('show_all_reservation') ||
            $authUser->hasPermission('show_all_reservation_of_agency')
        ) {
            $reservation = Reservation::findOrFail($request->id);

            if(
                !$authUser->hasPermission('manage_reservations') &&
                !$authUser->hasPermission('show_all_reservation') &&
                $authUser->hasPermission('show_all_reservation_of_agency')
            ) {
                if($authUser->work_at != $reservation->ressource->agency_id) {
                    abort(403);
                }
            }
            $reservation = $this->reservationAllInformations()->find($request->id);
            $response = [
                'reservation' => $reservation,
            ];
            return response()->json($response, 201);
        }

        abort(403);
    }

    public function store(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_reservations') &&
            !$authUser->hasPermission('create_reservation') &&
            !$authUser->hasPermission('create_reservation_of_agency')
        ) {
            abort(403);
        }

        // verifie si le client et la ressouce existe bien en BD
        $client = User::findOrFail($request->client_id);
        $ressource = Ressource::findOrFail($request->ressource_id);
        $agency_id = $ressource->agency_id;

        if(
            !$authUser->hasPermission('manage_reservations') &&
            !$authUser->hasPermission('create_reservation') &&
            $authUser->hasPermission('create_reservation_of_agency')
        ) {
            if($authUser->work_at != $agency_id) {
                abort(403);
            }
        }

        //====================================================================================
        $validator = Validator::make($request->all(),[
            'validity' => 'required|string|in:hour,midday,day,week,month',
            'midday_period' => 'string',
            'start_date' => 'required|date|after:'.$this->formatDate(Carbon::yesterday()),
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_hour' => 'string|in:05:00,06:00,07:00,08:00,09:00,10:00,11:00,12:00,
                            13:00,14:00,15:00,16:00,17:00,18:00,19:00,20:00,21:00,22:00',
            'end_hour' => 'string|in:05:00,06:00,07:00,08:00,09:00,10:00,11:00,12:00,
                            13:00,14:00,15:00,16:00,17:00,18:00,19:00,20:00,21:00,22:00|after:start_hour',
            'coupon' => 'string',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Reservation creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        //=====================================================================================
        //on verifie que si la start_date est aujourdhui alors l'start_hour doit etre superieur 
        // a l'heur actuelle
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $start_date_carbon = Carbon::parse($start_date);
        $end_date_carbon = Carbon::parse($end_date);
        $now = Carbon::now();
        if($this->formatDate($start_date_carbon) == $this->formatDate($now)) {
            if($this->formatHour(Carbon::parse($request->start_hour)) <= $this->formatHour($now)) {
                \LogActivity::addToLog("Reservation creation failed. Error: Invalid start hour $request->start_hour.");
                return response([
                    'errors' => [
                        'en' => "Invalid start hour $request->start_hour",
                        'fr' => "Heure de debut invalide $request->start_hour",
                    ]
                ], 422);
            }
        }

        //recuperer les jours feries de l'entreprise et on verifie 
        // si la start date ou la end_date n'est pas feriee
        $holidays = explode("," , \Options::getValue('holidays'));

        /*-------- start date --------- */
        if (in_array($start_date, $holidays)) {
            \LogActivity::addToLog("Reservation creation failed for client $client->lastname $client->fisrtname | ressource $ressource->id. The date $start_date is holiday.");
            return response([
                'errors' => [
                    'en' => "The date $start_date is holiday.",
                    'fr' => "La date $start_date est fériée.",
                ]
            ], 422);
        }

        /*-------- end date --------- */
        if (in_array($end_date, $holidays)) {
            \LogActivity::addToLog("Reservation creation failed for client $client->lastname $client->fisrtname | ressource $ressource->id. The date $start_date is holiday.");
            return response([
                'errors' => [
                    'en' => "The date $end_date is holiday.",
                    'fr' => "La date $end_date est fériée.",
                ]
            ], 422);
        }

        //========================================================================================
        /*-------- start date --------- */
        //recuperer le jour de la semaine de la date de debut
        $start_date_dayOfWeek = $start_date_carbon->translatedFormat('l');

        //verifie si le jour existe en bd
        if(
            !Openingday::where("name_en", $start_date_dayOfWeek)
                    ->orWhere("name_fr", $start_date_dayOfWeek)
                    ->exists()
        ){
            $agency_name = $ressource->agency->name;
            \LogActivity::addToLog("Reservation creation failed. Error: $start_date_dayOfWeek is not a day of the week.");
            return response([
                'errors' => [
                    'en' => "$start_date_dayOfWeek is not a day of the week.",
                    'fr' => "$start_date_dayOfWeek n'est pas un jour de la semaine.",
                ]
            ], 422);
        }

        // verifier si ce jour fait partie des jours d'ouverture de l'agence
        $openingday_id = Openingday::where("name_en", $start_date_dayOfWeek)
                                    ->orWhere("name_fr", $start_date_dayOfWeek)
                                    ->first()
                                    ->id;
        if(
            !DB::table('agencyOpeningdays')
                ->where('agency_id', $agency_id)
                ->where("openingday_id", $openingday_id)
                ->exists()
        ) {
            $agency_name = $ressource->agency->name;
            \LogActivity::addToLog("Reservation creation failed. Error: The agency $agency_name don't open on $start_date_dayOfWeek.");
            return response([
                'errors' => [
                    'en' => "The agency $agency_name don't open on $start_date_dayOfWeek.",
                    'fr' => "L'agence $agency_name n'ouvre pas le $start_date_dayOfWeek.",
                ]
            ], 422);
        }

        /*-------- end date --------- */
        //recuperer le jour de la semaine de la date de fin
        $end_date_dayOfWeek = $end_date_carbon->translatedFormat('l');

        //verifie si le jour existe en bd
        if(
            !Openingday::where("name_en", $end_date_dayOfWeek)
                    ->orWhere("name_fr", $end_date_dayOfWeek)
                    ->exists()
        ){
            $agency_name = $ressource->agency->name;
            \LogActivity::addToLog("Reservation creation failed. Error: $end_date_dayOfWeek is not a day of the week.");
            return response([
                'errors' => [
                    'en' => "$end_date_dayOfWeek is not a day of the week.",
                    'fr' => "$end_date_dayOfWeek n'est pas un jour de la semaine.",
                ]
            ], 422);
        }

        //verifier si ce jour fait partie des jours d'ouverture de l'agence
        $openingday_id = Openingday::where("name_en", $end_date_dayOfWeek)
                                    ->orWhere("name_fr", $end_date_dayOfWeek)
                                    ->first()
                                    ->id;
        if(
            !DB::table('agencyOpeningdays')
                ->where('agency_id', $agency_id)
                ->where("openingday_id", $openingday_id)
                ->exists()
        ) {
            $agency_name = $ressource->agency->name;
            \LogActivity::addToLog("Reservation creation failed. Error: The agency $agency_name don't open on $end_date_dayOfWeek.");
            return response([
                'errors' => [
                    'en' => "The agency $agency_name don't open on $end_date_dayOfWeek.",
                    'fr' => "L'agence $agency_name n'ouvre pas le $end_date_dayOfWeek.",
                ]
            ], 422);
        }

        // ==========================================================================================
        /*-------- start hour --------- */
        // on recupere l'heure d'ouverture et de fermeture de l'agence
        $agency_openingday = DB::table('agencyOpeningdays')->where("agency_id", $agency_id)
                                ->where("openingday_id", $openingday_id)
                                ->first();

        $opening_hour = $this->getCarbonHour($agency_openingday->from);
        $closing_hour = $this->getCarbonHour($agency_openingday->to);
        $start_hour = $this->getCarbonHour($request->start_hour);
        $end_hour = $this->getCarbonHour($request->end_hour);

        //et on verifie que l'heure de debut est compris entre l'heure d'ouverture et de fermeture
        if(!$start_hour->between($opening_hour, $closing_hour)) {
            $agency_name = $ressource->agency->name;
            \LogActivity::addToLog("Reservation creation failed. Error: The agency $agency_name open at $opening_hour and closes at $closing_hour at $request->start_hour");
            return response([
                'errors' => [
                    'en' => "The agency $agency_name is openend at $opening_hour and closes at $closing_hour at $request->start_hour",
                    'fr' => "L'agence $agency_name ouvre à $opening_hour et ferme à $closing_hour le $request->start_hour",
                ]
            ], 422);
        }


        /*-------- end hour --------- */
        //et on verifie que l'heure de fin est avant l'heure de fermeture de l'agence
        if($end_hour->isAfter($closing_hour)) {
            return "end_hour: $end_hour | opening_hour: $opening_hour | closing_hour: $closing_hour";
            $agency_name = $ressource->agency->name;
            \LogActivity::addToLog("Reservation creation failed. Error: The end hour $request->end_hour must after the closing hour $end_date_dayOfWeek for the agency $agency_name.");
            return response([
                'errors' => [
                    'en' => "The end hour $request->end_hour must after the closing hour $closing_hour for the agency $agency_name at $end_date_dayOfWeek.",
                    'fr' => "L'heure de fin $request->end_hour doit etre avant l'heure de fermeture $closing_hour pour l'agence $agency_name le $end_date_dayOfWeek.",
                ]
            ], 422);
        }

        // =========================================================================================
        // on determine la date et lheure de debut et de fin en fonction de la validite
        $validity = $request->validity;
        $start_date_confirmed = '';
        $end_date_confirmed = '';
        $start_hour_confirmed = '';
        $end_hour_confirmed = '';

        switch ($validity) {
            case 'hour':
                $start_date_confirmed = $this->formatDate($start_date_carbon);
                $end_date_confirmed = $this->formatDate($start_date_carbon);
                $start_hour_confirmed = $this->formatHour($start_hour);
                $end_hour_confirmed = $this->formatHour($end_hour);
                break;
            case 'midday':
                $start_date_confirmed = $this->formatDate($start_date_carbon);
                $end_date_confirmed = $this->formatDate($start_date_carbon);
                $midday_period = '';
                if($request->has("midday_period") && isset($request->midday_period)) {
                    if($request->midday_period == "afternoon") {
                        $midday_period = "afternoon";
                    } else {
                        $midday_period = "morning";
                    }
                } else {
                    $midday_period = "morning";
                }

                if($midday_period == "morning") {
                    $start_hour_confirmed = $this->formatHour($this->getCarbonHour("08:00"));
                    $end_hour_confirmed = $this->formatHour($this->getCarbonHour("14:00"));
                }

                if($midday_period == "afternoon") {
                    $start_hour_confirmed = $this->formatHour($this->getCarbonHour("14:00"));
                    $end_hour_confirmed = $this->formatHour($this->getCarbonHour("19:00"));
                }
                break;
            case 'day':
                $start_date_confirmed = $this->formatDate($start_date_carbon);
                $end_date_confirmed = $this->formatDate($end_date_carbon);
                $start_hour_confirmed = $this->formatHour($opening_hour);
                $end_hour_confirmed = $this->formatHour($closing_hour);
                break;
            case 'week':
                $start_date_confirmed = $this->formatDate($start_date_carbon);
                $end_date_confirmed = $this->formatDate($end_date_carbon);
                $start_hour_confirmed = $this->formatHour($opening_hour);
                $end_hour_confirmed = $this->formatHour($closing_hour);
                break;
            case 'month':
                $start_date_confirmed = $this->formatDate($start_date_carbon);
                $end_date_confirmed = $this->formatDate($end_date_carbon);
                $start_hour_confirmed = $this->formatHour($opening_hour);
                $end_hour_confirmed = $this->formatHour($closing_hour);
                break;
            default:
                # code...
                break;
        }

        // $diff_date = $this->diff_date($start_date_confirmed, $end_date_confirmed);
        // return $diff_date;

        // =============== creation de la reservation proprement dite =============

        //on verifie la ressource est disponible ce jour a cette heure
        
        $reservations_of_ressource =
            Reservation::where('ressource_id', $ressource->id)
            // ->where('start_date', '>=', $this->formatDate($now))
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
            ->get();

        return $reservations_of_ressource;

        /*-------- validity: day, week, month ---------*/
        $reservations_of_ressource_date =
            Reservation::where('ressource_id', $ressource->id)
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
            ->where(function ($query) {
                $query->where('state', 'confirmed')
                    ->orWhere('state', 'totally paid');
            })
            ->get();

        return $reservations_of_ressource_date;

        /*-------- validity: hour, midday ---------*/
        $reservations_of_ressource_date_hour=
            Reservation::where('ressource_id', $ressource->id)
            ->where(function ($query) use($start_date_confirmed, $end_date_confirmed) {
                $query->where('start_date', $start_date_confirmed)
                    ->where('end_date', $end_date_confirmed);
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
            ->get();

        return $reservations_of_ressource_date_hour;

        return "start_date: $start_date_confirmed  end_date: $end_date_confirmed
                start_hour: $start_hour_confirmed  end_hour: $end_hour_confirmed";

        $reservation = Reservation::create($validator->validated());
        $reservation->space_id = $space->id;
        $reservation->created_by = $authUser->id;
        $reservation->agency_id = $confirm_agency_id;
        $reservation->save();

        $response = [
            'message' => "The reservation $reservation->id successfully created",
        ];

        \LogActivity::addToLog("New reservation created. reservation id: $reservation->id");

        return response($response, 201);
    }

    public function update(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_reservation') &&
            !$authUser->hasPermission('edit_reservation') &&
            !$authUser->hasPermission('edit_reservation_of_agency')
        ) {
            abort(403);
        }
        $validator = Validator::make($request->all(),[
            'quantity' => 'required|integer|min:1',
            'price_hour' => 'required|integer|min:1',
            'price_midday' => 'required|integer|min:1',
            'price_day' => 'required|integer|min:1',
            'price_week' => 'required|integer|min:1',
            'price_month' => 'required|integer|min:1',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Reservation updation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $agency = Agency::findOrFail($request->agency_id);
        $space = Space::findOrFail($request->space_id);
        $reservation = Reservation::findOrFail($request->id);

        $existing_reservation = Reservation::where('agency_id', $agency->id)
        ->where('space_id', $space->id)->first();

        if($authUser->hasPermission('edit_reservation_of_agency')) {
            if(
                $agency->id != $authUser->work_at &&
                !$authUser->hasPermission('manage_reservation') &&
                !$authUser->hasPermission('edit_reservation')
            ) {
                abort(403);
            }
            $confirm_agency_id = $authUser->work_at;
        }

        if($existing_reservation && $existing_reservation->id != $request->id){
            \LogActivity::addToLog("Reservation updation failed. Error: The selected space has been already created in this agency");
            return response([
                'errors' => "The selected space has been already created in this agency.",
            ], 422);
        }

        if(
            $authUser->hasPermission('manage_reservation') ||
            $authUser->hasPermission('edit_reservation')
        ) {
            $confirm_agency_id = $agency->id;
        }

        $reservation = Reservation::findOrFail($request->id);
        if($request->has('quantity') && isset($request->quantity)) {
            $reservation->quantity = $request->quantity;
        }
        if($request->has('price_hour') && isset($request->price_hour)) {
            $reservation->price_hour = $request->price_hour;
        }
        if($request->has('price_midday') && isset($request->price_midday)) {
            $reservation->price_midday = $request->price_midday;
        }
        if($request->has('price_day') && isset($request->price_day)) {
            $reservation->price_day = $request->price_day;
        }
        if($request->has('price_week') && isset($request->price_week)) {
            $reservation->price_week = $request->price_week;
        }
        if($request->has('price_month') && isset($request->price_month)) {
            $reservation->price_month = $request->price_month;
        }
        $reservation->space_id = $space->id;
        $reservation->created_by = $authUser->id;
        $reservation->agency_id = $confirm_agency_id;
        $reservation->update();

        $response = [
            'message' => "The reservation $reservation->id successfully updated.",
        ];

        \LogActivity::addToLog("The reservation $reservation->id has been update");

        return response($response, 201);
    }

    public function cancel(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('manage_reservations') ||
            $authUser->hasPermission('cancel_all_reservation') ||
            $authUser->hasPermission('cancel_reservation_of_agency') ||
            $authUser->hasPermission('cancel_own_reservation')
        ) {
            $reservation = Reservation::findOrFail($request->id);
            if(
                !$authUser->hasPermission('manage_reservations') &&
                !$authUser->hasPermission('cancel_all_reservation')
            ) {
                if($authUser->hasPermission('cancel_reservation_of_agency')) {
                    if($authUser->work_at != $reservation->ressource->agency_id) {
                        abort(403);
                    }
                }
                if($authUser->hasPermission('cancel_own_reservation')) {
                    if($authUser->id != $reservation->created_by) {
                        abort(403);
                    }
                }
            }

            if (!Hash::check($request->password, $authUser->password)) {
                $response = [
                    'password' => "Wrong password. $request->password"
                ];
                \LogActivity::addToLog("Reservation $reservation->id cancellation failed. error: Wrong password");
                return response($response, 422);
            }

            if($request->undo_cancellation){
                if($reservation->amount_due == 0 ){
                    $reservation->state = 'totally paid';
                }
                if($reservation->amount_due >= $reservation->initial_amount ){
                    $reservation->state = 'confirmed';
                }
                // if(0 < $reservation->amount_due < ($reservation->initial_amount/2)){
                $half_amount = $reservation->initial_amount/2;
                // if(0 < $reservation->amount_due && $reservation->amount_due < $half_amount){
                if(
                    $reservation->amount_due < $reservation->initial_amount &&
                    $reservation->amount_due > $half_amount
                ){
                    $reservation->state = 'partially paid';
                }
                if($reservation->amount_due == $reservation->initial_amount ){
                    $reservation->state = 'pending';
                }
                $response = [
                    'message' => "The reservation $reservation->id 's cancellation is stopped 
                    half_amount $half_amount
                    due $reservation->amount_due initial $reservation->initial_amount",
                ];
            } else {
                $validator = Validator::make($request->all(),[
                    'reason_for_cancellation' => 'required|string|max:250',
                ]);
    
                if($validator->fails()){
                    \LogActivity::addToLog("Reservation $reservation->id cancellation failed. ".$validator->errors());
                    return response([
                        'errors' => $validator->errors(),
                    ], 422);
                }
                $reservation->state = 'cancelled';
                $reservation->reason_for_cancellation = $request->reason_for_cancellation;
                $response = [
                    'message' => "The reservation $reservation->id successfully cancelled",
                ];
                $reservation->cancelled_by = $authUser->id;
                $reservation->cancelled_at = now();
            }
            $reservation->save();
            \LogActivity::addToLog("The reservation $reservation->id state updated to $reservation->state.");
            return response($response, 201);
        }
        abort(403);
    }

    /* ========================= custom functions ============================ */
    public function formatDate($date)
    {
        return $date->format('Y-m-d');
    }

    public function formatHour($hour)
    {
        return $hour->format("H:i");
    }

    public function getCarbonHour($hour)
    {
        return Carbon::createFromFormat("H:i", $hour);
    }

    public function getCarbonDate($date)
    {
        return Carbon::createFromFormat("Y-m-d H:i:s", $date);
    }

    public function diff_date($start_date, $end_date)
    {
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $diff_months = $end_date->diffInMonths($start_date);
        $rest_days= $end_date->diffInDays($start_date) % 30;
        
        if ($rest_days> 0) {
            $diff_weeks = floor($rest_days/ 7);
            $rest_days_week = $rest_days% 7;
        
            if ($rest_days_week > 0) {
                $diff_days = $rest_days_week;
                $rest_hours = $end_date->diffInHours($start_date) % 24;
            } else {
                $diff_days = 0;
                $rest_hours = 0;
            }
        } else {
            $diff_weeks = 0;
            $diff_days = 0;
            $rest_hours = $end_date->diffInHours($start_date);
        }
        
        /*
        echo "Différence en mois : $diff_months mois \n";
        echo "Différence en semaines : $diff_weeks semaines \n";
        echo "Différence en jours : $diff_days jours \n";
        echo "Différence en heures : $rest_hours heures"; */

        return [
            'diff_months' => $diff_months,
            'diff_weeks' => $diff_weeks,
            'diff_days' => $diff_days,
            'rest_hours' => $rest_hours,
        ];
    }
}

/*
        Reservation::where('ressource_id', $ressource->id)
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
        ->where(
            function ($query)
            use($start_date_confirmed, $end_date_confirmed, $start_hour_confirmed, $end_hour_confirmed) {
                $query->where('start_date', $start_date_confirmed)
                    ->where('end_date', $end_date_confirmed)
                    ->where(
                        function ($query)
                        use ($start_hour_confirmed, $end_hour_confirmed) {
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
                    });
        })
        ->where(function ($query) {
            $query->where('state', 'confirmed')
                ->orWhere('state', 'totally paid');
        })
        ->get();
*/
