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
        // return 'yes';
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

        if($authUser->hasPermission('create_reservation_of_agency')) {
            if($authUser->work_at != $agency_id) {
                abort(403);
            }
        }

        $validator = Validator::make($request->all(),[
            // 'client_id' => 'required|integer|min:1',
            // 'ressource_id' => 'required|integer|min:1',
            'validity' => 'required|string',
            'midday_period' => 'string',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'start_hour' => 'required|string',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Reservation creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        //recuperer les jours feries de l'entreprise et on verifie si la date choisie n'est pas feriee
        $holidays = explode("," , \Options::getValue('holidays'));
        $start_date = $request->start_date;

        if (in_array($start_date, $holidays)) {
            \LogActivity::addToLog("Reservation creation failed for client $client->lastname $client->fisrtname | ressource $ressource->id. The date $start_date is holiday.");
            return response([
                'errors' => [
                    'en' => "The date $start_date is holiday.",
                    'fr' => "La date $start_date est fériée.",
                ]
            ], 422);
        }

        //reuperer le jour de la semaine de la date de debut
        $start_date_carbon = Carbon::parse($start_date);
        $dayOfWeek = $start_date_carbon->translatedFormat('l');

        if(
            Openingday::where("name_en", $dayOfWeek)
                    ->orWhere("name_fr", $dayOfWeek)
                    ->exists()
        ){
            $openingday_id = Openingday::where("name_en", $dayOfWeek)
                                        ->orWhere("name_fr", $dayOfWeek)
                                        ->first()
                                        ->id;

            if(DB::table('agencyOpeningdays')->where('agency_id', $agency_id)->where("openingday_id", $openingday_id)->exists()) {
                $agency_openingday = DB::table('agencyOpeningdays')->where("agency_id", $agency_id)
                                                    ->where("openingday_id", $openingday_id)
                                                    ->first();
                
                return $agency_openingday;
            }
            return 'wanda';
        }
        return '';
        if(
            Reservation::where('agency_id', $agency->id)
                    ->where('space_id', $space->id
                    )->exists()
        ){
            \LogActivity::addToLog("Reservation creation failed. Error: The selected space has been already created in this agency");
            return response([
                'errors' => "The selected space has been already created in this agency",
            ], 422);
        }

        if($authUser->hasPermission('create_reservation_of_agency')) {
            if(
                $agency->id != $authUser->work_at &&
                !$authUser->hasPermission('manage_reservation') &&
                !$authUser->hasPermission('create_reservation')
            ) {
                abort(403);
            }
            $confirm_agency_id = $authUser->work_at;
        }

        if(
            $authUser->hasPermission('manage_reservation') ||
            $authUser->hasPermission('create_reservation')
        ) {
            $confirm_agency_id = $agency->id;
        }

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
}