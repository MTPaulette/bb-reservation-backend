<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Agency;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Ressource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $authUser = $request->user();
        if(!$authUser->hasPermission('view_dashboard')) {
            abort(403);
        }

        $validator = Validator::make($request->all(),[
            'year' => 'integer|lte:'.date('Y'),
            'month' => 'integer|lte:'.date('m'),
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Reservation creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $year = date('Y');
        if($request->has("year") && isset($request->year)) {
            $year = $request->year;
        }

        $month = date('m');
        if($request->has("month") && isset($request->month)) {
            $month = $request->month;
        }

        // Nombre total de clients
        $totalClients = User::where('role_id', 2)->count();

        // Nombre total de staff
        $totalStaff = User::where('role_id', 1)->orWhere('role_id', 3)->count();

        // Nombre total de réservations
        $totalReservations = Reservation::whereNot('state', 'cancelled')->count();

        // Nombre total de réservations
        $totalCancelledReservations = Reservation::where('state', 'cancelled')->count();

        // Nombre total de ressources
        $totalRessources = Ressource::count();

        // Revenu total
        $totalRevenue = Reservation::sum('initial_amount');

        // total montant restant
        $totalDue = Reservation::sum('amount_due');

        // Nombre total de paiement
        $totalPayments = Payment::sum('amount');

        // Réservations en cours
        $now = Carbon::now();
        $this_hour = $now->copy()->format("H:i");
        $today = $now->copy()->format('Y-m-d');

        $currentReservations_query =
            Reservation::with([
                'client' => function($query) {
                    $query->select('id', 'lastname', 'firstname');
                },
                'createdBy' => function($query) {
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
            ])
            ->where(function ($query) use ($today) {
                $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
            })
            ->where('end_hour', '>=', $this_hour)
            ->whereNot('state', 'cancelled');

        $currentReservations = $currentReservations_query->orderBy('start_hour')->get();
        $totalCurrentReservations = $currentReservations_query->count();

        //l'agency avec le plus de ressources
        $agencyWithMostRessources = Agency::with('ressources')
                            ->withCount('ressources')
                            ->orderByDesc('ressources_count')
                            ->first();

        // Top 5 des clients les plus actifs
        $topClients = User::where('role_id', 2)
                        ->with('reservations')
                        ->withCount('reservations')
                        ->orderByDesc('reservations_count')
                        ->get()->take(5);
        
        // Meilleure agency
        $bestAgency = Agency::with('reservations')
                            ->withCount('reservations')
                            ->orderByDesc('reservations_count')
                            ->first();

        // agence et la somme de paiements fait par mois
        $agencies = Agency::all();
        $agency_with_payments_per_month = $agencies->map(function ($agency) use ($year) {
            $agency_id = $agency->id;
            $payments = Payment::whereHas('reservation.ressource.agency', function ($query) use ($agency_id) {
                $query->where('id', $agency_id);
            })->selectRaw('*')
            ->whereYear('payments.created_at', "2024")
            // ->whereYear('payments.created_at', $year) ici
            ->groupBy(
                'id', 'month', 'amount',
                'payment_method', 'payment_status', 'transaction_id',
                'bill_number', 'reservation_id', 'processed_by',
                'created_at', 'updated_at'
            )
            ->selectRaw('id, SUM(payments.amount) as total, MONTH(payments.created_at) as month')
            ->get();

            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $payment = $payments->where('month', $i);
                $total_payment = 0;
                foreach ($payment as $p) {
                    $total = $p->total ? $p->total : 0;
                    $total_payment = $total_payment + $total;
                }
                $data[] = $total_payment;
            }

            return [
                'name' => $agency->name,
                'data' => $data,
            ];
        })->toArray();

        // Meilleur mois
        $bestMonth = Reservation::where('state', 'totally paid')
                            ->whereYear('created_at', "2024")
                            // ->whereYear('created_at', $year) ici
                            ->groupBy('month')
                            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                            ->get()->sortByDesc('count')->first();

        //Meilleur staff
        $bestStaff = User::whereNot('role_id', 2)
                            ->whereHas('createdReservations')
                            ->with('createdReservations')
                            ->withCount('createdReservations')
                            ->orderByDesc('created_reservations_count')
                            ->first();

        //Meilleur client
        $bestClient = $topClients[0];

        //Meilleure ressource
        $bestRessource = Ressource::whereHas('reservations')
                            // ->with('reservations')
                            ->with([
                                'reservations',
                                'space' => function($query) {
                                    $query->select('id', 'name');
                                },
                            ])
                            ->withCount('reservations')
                            ->orderByDesc('reservations_count')
                            ->first();

        // ressource et le total de reservations faites pour cette ressource suivant les agences
        $ressource_with_reservations = $agencies->map(function ($agency) use ($year, $month) {
            $ressources = $agency->ressources;
            $data = [];

            foreach ($ressources as $ressource) {
                $reservations = Reservation::where('ressource_id', $ressource->id)
                    ->where('state', 'totally paid')
                    ->whereYear('created_at', "2024")
                    // ->whereYear('created_at', $year) ici
                    // ->whereMonth('created_at', $month)
                    ->count();

                $label[] = $ressource->space->name;
                $data[] = $reservations;
            }

            return [
                'name' => $agency->name,
                'label' => $label,
                'data' => $data,
            ];
        })->toArray();

        /*========= revenue pour chaque jour de la semaine ici
                ->where('created_at', '>=', Carbon::now()->startOfWeek())
                ->where('created_at', '<=', Carbon::now()->endOfWeek())*/
        $reservations_not_cancelled_of_week =
            Reservation::whereNot('state', 'cancelled')
                ->where('created_at', '>=', Carbon::parse("2024-12-10T17:16:22.277289Z")->startOfWeek())
                ->where('created_at', '<=', Carbon::parse("2024-12-10T17:16:22.277289Z")->endOfWeek())
                ->get();

        $revenu_of_current_week = [];

        foreach ($reservations_not_cancelled_of_week as $reservation) {
            $dayOfWeek = $reservation->created_at->dayOfWeek;
            $revenu_of_current_week[$dayOfWeek] = ($revenu_of_current_week[$dayOfWeek] ?? 0) + $reservation->initial_amount;
        }

        // Remplir les jours manquants avec 0
        for ($i = 0; $i < 7; $i++) {
        if (!isset($revenu_of_current_week[$i])) {
            $revenu_of_current_week[$i] = 0;
        }
        }

        // Réorganiser le tableau pour commencer par lundi
        $revenu_of_current_week = array_values($revenu_of_current_week);

        /*========= vente pour chaque jour de la semaine ici
        $payments_of_week = Payment::where('created_at', '>=', Carbon::now()->startOfWeek())
                            ->where('created_at', '<=', Carbon::now()->endOfWeek())
                            ->get();
        */
        $payments_of_week = Payment::where('created_at', '>=', Carbon::parse("2024-12-10T17:16:22.277289Z")->startOfWeek())
                            ->where('created_at', '<=', Carbon::parse("2024-12-10T17:16:22.277289Z")->endOfWeek())
                            ->get();
    
        $payment_of_current_week = [];
        
        foreach ($payments_of_week as $payment) {
            $dayOfWeek = $payment->created_at->dayOfWeek;
            $payment_of_current_week[$dayOfWeek] = ($payment_of_current_week[$dayOfWeek] ?? 0) + $payment->amount;
        }
        
        // Remplir les jours manquants avec 0
        for ($i = 0; $i < 7; $i++) {
            if (!isset($payment_of_current_week[$i])) {
                $payment_of_current_week[$i] = 0;
            }
        }
        
        // Réorganiser le tableau pour commencer par lundi
        $payment_of_current_week = array_values($payment_of_current_week);
        $payment_revenu_of_current_week = [
            [
                'name' => 'revenu',
                'data' => $revenu_of_current_week,
            ],
            [
                'name' => 'payment',
                'data' => $payment_of_current_week,
            ],
        ];

        $response = [
            'payment_revenu_of_current_week' => $payment_revenu_of_current_week,
            'year' => $year,
            'month' => $month,
            'bestAgency' => $bestAgency,
            'bestMonth' => $bestMonth,
            'bestStaff' => $bestStaff,
            'bestClient' => $bestClient,
            'bestRessource' => $bestRessource,
            'ressource_with_reservations' => $ressource_with_reservations,
            'agency_with_payments_per_month' => $agency_with_payments_per_month,
            'totalClients' => $totalClients,
            'totalReservations' => $totalReservations,
            'totalCancelledReservations' => $totalCancelledReservations,
            'totalRessources' => $totalRessources,
            'totalStaff' => $totalStaff,
            'totalPayments' => $totalPayments,
            'totalRevenue' => $totalRevenue,
            'totalDue' => $totalDue,
            'totalCurrentReservations' => $totalCurrentReservations,
            'currentReservations' => $currentReservations,
            'topClients' => $topClients,
            'agencyWithMostRessources' => $agencyWithMostRessources,
        ];
        return response()->json($response, 201);
    }
}

/*
 [
      {
        name: "Product One",
        data: [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 30, 45],
      },

      {
        name: "Product Two",
        data: [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39, 51],
      },
    ]
      
  const series = [65, 34, 12, 56];
      */