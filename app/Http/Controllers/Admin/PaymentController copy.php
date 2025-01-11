<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Helpers\Reservation as HelpersReservation;
use App\Helpers\User as HelpersUser;
use App\Models\Payment;
use App\Models\Reservation;
use App\Notifications\NewPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function paymentWithCreator()
    {
        return
        Payment::with([
            'processedBy' => function($query) {
                $query->select('id', 'lastname', 'firstname');
            }
        ]);
    }

    public function index(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('manage_payments') ||
            $authUser->hasPermission('show_all_payment')
        ) {
            $payments = $this->paymentWithCreator()->get();
            // $payments = Payment::get();
            return response()->json($payments, 201);
        }

        abort(403);
    }

    public function store(Request $request)
    {
        $authUser = $request->user();
        if(
            // !$authUser->hasPermission('process_payment') &&
            // !$authUser->hasPermission('create_payment')
            !$authUser->hasPermission('manage_reservations') &&
            !$authUser->hasPermission('create_reservation') &&
            !$authUser->hasPermission('create_reservation_of_agency')
        ) {
            abort(403);
        }

        //verifie si la reservation existe
        if(!Reservation::where("id", $request->reservation_id)->exists()) {
            \LogActivity::addToLog("Payment creation failed. Reservation with id: $request->reservation_id not found.");
            return response([
                'errors' => [
                    'en' => "reservation not found",
                    'fr' => "réservation non trouvée",
                ]
            ], 404);
        }
        $reservation = Reservation::find($request->reservation_id);

        // on verifie les droits sur cette reservation
        $agency_id = $reservation->ressource->agency_id;
        if(
            !$authUser->hasPermission('manage_reservations') &&
            !$authUser->hasPermission('create_reservation') &&
            $authUser->hasPermission('create_reservation_of_agency')
        ) {
            if($authUser->work_at != $agency_id) {
                abort(403);
            }
        }

        $validator = Validator::make($request->all(),[
            'amount' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:Bank,Cash,MTN Money,Orange Money',
            'payment_status' => 'nullable|string',
            'transaction_id' => 'nullable|string|unique:payments',
            'bill_number' => 'nullable|string|unique:payments',
            'note' => 'nullable|string|',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Payment creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        //on verifie la reservation n'est pas une date de fin depasse
        $now = Carbon::now();
        if($reservation->end_date < $now->format('Y-m-d')) {
        \LogActivity::addToLog("Payment creation failed. Error: You can not make payment for the reservation id $reservation->id which end date $reservation->end_date is already passed.");
        return response([
            'errors' => [
                'en' => "You can not make payment for reservation id $reservation->id which end date is already passed.",
                'fr' => "Vous ne pouvez pas effectuer de paiement pour la réservation id $reservation->id dont la date de fin $reservation->end_date est déjà passée.",
            ]
        ], 422);
        }

        //on verifie la ressource est disponible ce jour a cette heure
        $ressource = $reservation->ressource;
        $start_date_confirmed = $reservation->start_date;
        $end_date_confirmed = $reservation->end_date;
        $start_hour_confirmed = $reservation->start_hour;
        $end_hour_confirmed = $reservation->end_hour;

        $isAvailable = HelpersReservation::isAvailable(
            $ressource,
            $start_date_confirmed, $end_date_confirmed,
            $start_hour_confirmed, $end_hour_confirmed
        );

        if(!$isAvailable){
            \LogActivity::addToLog("Payment creation  failed. Error: The ressource id $ressource->id is already busy from $start_date_confirmed to $end_date_confirmed between $start_hour_confirmed and $end_hour_confirmed.");
            return response([
                'errors' => [
                    'en' => "This ressource is already busy from $start_date_confirmed to $end_date_confirmed between $start_hour_confirmed and $end_hour_confirmed.",
                    'fr' => "Cette ressource est déjà occupée du $start_date_confirmed au $end_date_confirmed entre $start_hour_confirmed et $end_hour_confirmed.",
                ]
            ], 422);
        }

        // verifie si la reservation n'est pas annulee
        if($reservation->state == 'cancelled') {
            \LogActivity::addToLog("Payment creation failed. You can not pay for a cancelled reseration.");
            return response([
                'errors' => [
                    'en' => "You can not pay for a cancelled reseration.",
                    'fr' => "Vous ne pouvez pas payer pour une réservation annulée.",
                ]
            ], 422);
        }

        // verifie si la reservation n'est pas deja totalement payee
        if($reservation->amount_due == 0) {
            \LogActivity::addToLog("Payment creation failed. The reseration is already totally paid.");
            return response([
                'errors' => [
                    'en' => "The reseration is totally paid.",
                    'fr' => "La réservation est déjà totallement payée.",
                ]
            ], 422);
        }
        //etudie la methode payment: 
        //pour le payment en cash, le numero de facture est obligatoire, pour le reste l'id de le transaction
        if($request->payment_method == 'Cash') {
            $validator = Validator::make($request->all(),[
                'bill_number' => 'required|string',
            ]);
    
            if($validator->fails()){
                \LogActivity::addToLog("Payment creation failed. ".$validator->errors());
                return response([
                    'errors' => $validator->errors(),
                ], 422);
            }
        } else {
            $validator = Validator::make($request->all(),[
                'transaction_id' => 'required|string',
            ]);
    
            if($validator->fails()){
                \LogActivity::addToLog("Payment creation failed. ".$validator->errors());
                return response([
                    'errors' => $validator->errors(),
                ], 422);
            }
        }

        //compare le montant au montant restant
        if($request->amount > $reservation->amount_due) {
            \LogActivity::addToLog("Payment creation failed. The amount $request->amount is greater than the reservation amount due $reservation->amount_due.");
            return response([
                'errors' => [
                    'en' => "The amount $request->amount is greater than the reservation amount due $reservation->amount_due.",
                    'fr' => "Le montant $request->amount est supérieur au montant restant de la réservation $reservation->amount_due.",
                ]
            ], 422);
        }

        //creer le paiement
        $payment = new Payment();
        $payment->amount = $request->amount;
        $payment->payment_method = $request->has("payment_method") && isset($request->payment_method) ? $request->payment_method : null;
        $payment->payment_status = $request->has("payment_status") && isset($request->payment_status) ? $request->payment_status : null;
        $payment->transaction_id = $request->has("transaction_id") && isset($request->transaction_id) ? $request->transaction_id : null;
        $payment->bill_number = $request->has("bill_number") && isset($request->bill_number) ? $request->bill_number : null;
        $payment->reservation_id = $reservation->id;
        $payment->processed_by = $authUser->id;
        $payment->save();

        //mettre a jour le montant restant et le statut de la reservation
        $new_amount_due = $reservation->amount_due - $request->amount;
        $reservation->amount_due = $new_amount_due;
        $reservation->state = HelpersReservation::getState($reservation->initial_amount, $new_amount_due);
        $reservation->note = $request->note;
        $reservation->save();

        // envoyer la notification du paiement au client, au superadmin et aux admins de l'agence
        $superadmin_admins = HelpersUser::getSuperadminAndAdmins($reservation->ressource->agency_id);
        $client = $reservation->client;

        foreach($superadmin_admins as $admin) {
            $admin->notify(new NewPayment($reservation, $payment));
        }
        $client->notify(new NewPayment($reservation, $payment));

        $response = [
            'message' => "The payment $payment->id successfully created",
        ];

        \LogActivity::addToLog("New payment created. payment id: $payment->id for reservation id: $reservation->id");
        return response($response, 201);
    }
}
