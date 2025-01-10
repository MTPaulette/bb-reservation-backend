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
            'period' => 'nullable|date',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Reservation creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $period = $request->period;
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
                        ->when($period, function ($query) use($period) {
                            $query->whereHas('reservations', function($query) use($period) {
                                $query->whereYear('reservations.created_at', Carbon::parse($period)->year);
                            });
                        })
                        ->withCount([
                            'reservations AS reservations_count' => function ($query) use ($period) {
                                $query->when($period, function ($query) use ($period) {
                                    $query->whereYear('reservations.created_at', Carbon::parse($period)->year);
                                });
                            }
                        ])
                        ->orderByDesc('reservations_count')
                        ->get()
                        ->take(5);


        // agence et la somme de paiements fait par mois
        $agencies = Agency::all();
        $agency_with_payments_per_month = $agencies->map(function ($agency) use ($period) {
            $agency_id = $agency->id;
            $payments = Payment::whereHas('reservation.ressource.agency', function ($query) use ($agency_id) {
                $query->where('id', $agency_id);
            })->selectRaw('*')
            ->when($period, function ($query, $period) {
                $query->whereYear('payments.created_at', Carbon::parse($period)->year);
            })
            ->when(!$period, function ($query) {
                $query->whereYear('payments.created_at', Date('Y'));
            })
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
        
        // Meilleure agency
        $bestAgency = Agency::with('reservations')
                            ->when($period, function ($query) use($period) {
                                $query->whereHas('reservations', function($query) use($period) {
                                    $query->whereYear('reservations.created_at', Carbon::parse($period)->year);
                                });
                            })
                            ->withCount([
                                'reservations AS reservations_count' => function ($query) use ($period) {
                                    $query->when($period, function ($query) use ($period) {
                                        $query->whereYear('reservations.created_at', Carbon::parse($period)->year);
                                    });
                                }
                            ])
                            ->orderByDesc('reservations_count')
                            ->first();

        //Meilleur staff
        $bestStaff = User::whereNot('role_id', 2)
                            // ->whereHas('createdReservations')
                            ->when($period, function ($query) use($period) {
                                $query->whereHas('createdReservations', function($query) use($period) {
                                    $query->whereYear('reservations.created_at', Carbon::parse($period)->year);
                                });
                            })
                            ->with('createdReservations')
                            ->withCount('createdReservations')
                            ->withCount([
                                'createdReservations AS created_reservations_count' => function ($query) use ($period) {
                                    $query->when($period, function ($query) use ($period) {
                                        $query->whereYear('reservations.created_at', Carbon::parse($period)->year);
                                    });
                                }
                            ])
                            ->orderByDesc('created_reservations_count')
                            ->first();

        // Meilleur mois
        $bestMonth = Reservation::where('state', 'totally paid')
                            ->when($period, function ($query) use($period) {
                                $query->whereYear('created_at', Carbon::parse($period)->year);
                            })
                            ->when(!$period, function ($query) {
                                $query->whereYear('created_at', Date('Y'));
                            })
                            ->groupBy('month')
                            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                            ->get()->sortByDesc('count')->first();

        //Meilleur client
        $bestClient = (sizeof($topClients)) > 0 ? $topClients[0] : null;

        //Meilleure ressource
        $bestRessource = Ressource::whereHas('reservations')
                            ->with([
                                'reservations',
                                'space' => function($query) {
                                    $query->select('id', 'name');
                                },
                            ])
                            ->when($period, function ($query) use($period) {
                                $query->whereHas('reservations', function($query) use($period) {
                                    $query->whereYear('reservations.created_at', Carbon::parse($period)->year);
                                });
                            })
                            ->withCount([
                                'reservations AS reservations_count' => function ($query) use ($period) {
                                    $query->when($period, function ($query) use ($period) {
                                        $query->whereYear('reservations.created_at', Carbon::parse($period)->year);
                                    });
                                }
                            ])
                            ->orderByDesc('reservations_count')
                            ->first();

        // ressource et le total de reservations faites pour cette ressource suivant les agences
        $ressource_with_reservations = $agencies->map(function ($agency) use ($period) {
            $ressources = $agency->ressources;
            $data = [];

            foreach ($ressources as $ressource) {
                $reservations = Reservation::where('ressource_id', $ressource->id)
                    ->where('state', 'totally paid')
                    ->when($period, function ($query) use($period) {
                        $query->whereYear('created_at', Carbon::parse($period)->year);
                    })
                    ->when(!$period, function ($query) {
                        $query->whereYear('created_at', Date('Y'));
                    })
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


        /*========= revenue pour chaque jour de la semaine */
        $reservations_not_cancelled_of_week =
            Reservation::whereNot('state', 'cancelled')
                ->when($period, function ($query, $period) {
                    $query->where('created_at', '>=', Carbon::parse($period)->startOfWeek())
                            ->where('created_at', '<', Carbon::parse($period)->endOfWeek());
                })
                ->when(!$period, function ($query) {
                    $query->where('created_at', '>=', Carbon::now()->startOfWeek())
                            ->where('created_at', '<', Carbon::now()->endOfWeek());
                })
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

        /*========= vente pour chaque jour de la semaine */
        $payments_of_week =
            Payment::when($period, function ($query, $period) {
                    $query->where('created_at', '>=', Carbon::parse($period)->startOfWeek())
                        ->where('created_at', '<', Carbon::parse($period)->endOfWeek());
                })
                ->when(!$period, function ($query) {
                    $query->where('created_at', '>=', Carbon::now()->startOfWeek())
                        ->where('created_at', '<', Carbon::now()->endOfWeek());
                })
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
            'year' => $period,
            'month' => $period,
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