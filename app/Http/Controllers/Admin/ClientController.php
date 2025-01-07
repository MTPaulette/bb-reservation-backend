<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Activity_log;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function userAllInformations()
    {
        return
        User::with([
            'createdBy' => function($query) {
                $query->select('id', 'lastname', 'firstname');
            },
            'suspendedBy' => function($query) {
                $query->select('id', 'lastname', 'firstname');
            },
        ]);
    }

    public function index(Request $request)
    {
        if(!$request->user()->hasPermission('show_all_client')) {
            abort(403);
        }
        $clients = User::withRole()->where('roles.name', 'client')->get()->toArray();
        return response()->json($clients, 201);
    }

    public function show(Request $request)
    {
        $authUser = $request->user();
        if( $authUser->hasPermission('view_client') || $request->id == $authUser->id ){
            $user = User::withRole()->findOrFail($request->id);
            if($user->role == 'client') {
                $user = $this->userAllInformations()->findOrFail($request->id);
                $reservations_results =
                    Reservation::where('reservations.client_id', '=', $request->id)
                    ->with([
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
                    ->orderByDesc('reservations.created_at')
                    ->get();
    
                $reservations = [];
                foreach ($reservations_results as $reservation) {
                    array_push($reservations, $reservation);
                };

                $response = [
                    'totalReservations' => sizeof($reservations),
                    'totalCoupons' => $user->coupons->count(),
                    'user' => $user,
                    'coupons' => $user->coupons,
                    'reservations' => $reservations,
                ];
                return response()->json($response, 201);
            }
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->user()->hasPermission('create_client')) {
            abort(403);
        }
        $validator = Validator::make($request->all(),[
            'lastname' => 'required|string|max:50',
            'firstname' => 'required|string|max:50',
            'email' => 'required|email|unique:users|max:250',
            'password' => 'required|string|min:8|max:50',
            'phonenumber' => 'string|min:9|max:250',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Client creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create($validator->validated());

        $user = User::where('email', $request->email)->first();
        $user->role_id = 2;
        $user->created_by = $request->user()->id;
        $user->save();
        $response = [
            'message' => "The client $user->firstname account successfully created",
        ];

        \LogActivity::addToLog("New client created. client name: $user->lastname $user->firstname");

        return response($response, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->user()->hasPermission('edit_client')) {
            $validator = Validator::make($request->all(),[
                'lastname' => 'string|max:50',
                'firstname' => 'string|max:50',
                // 'phonenumber' => 'string',
            ]);

            if($validator->fails()){
                \LogActivity::addToLog("Fail to update client's informations. ".$validator->errors());
                return response([
                    'errors' => $validator->errors(),
                ], 422);
            }
            $user = User::withRole()->findOrFail($request->id);
            if($user->role == 'client') {
                if($request->has('lastname') && isset($request->lastname)) {
                    $user->lastname = $request->lastname;
                }
                if($request->has('firstname') && isset($request->firstname)) {
                    $user->firstname = $request->firstname;
                }
                if($request->has('phonenumber') && isset($request->phonenumber)) {
                    $user->phonenumber = $request->phonenumber;
                }

                $user->update();
                \LogActivity::addToLog("The client $user->lastname $user->firstname has been updated.");
                return response($user, 201);
            }
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->user()->hasPermission('delete_client')) {
            $authUser = $request->user();
            $user = User::withRole()->findOrFail($request->id);
            if($user->role == 'client') {
                if (! Hash::check($request->password, $authUser->password)) {
                    $response = [
                        'password' => 'Wrong password.'
                    ];
                    \LogActivity::addToLog("Fail to delete client $user->lastname $user->firstname. error: Wrong password");
                    return response($response, 422);
                }

                // check if the user has already been make reservation
                $reservations = Reservation::where('created_by', $request->id)
                                    ->orWhere('receiver_user_id', $request->id)
                                    ->orWhere('giver_user_id', $request->id)
                                    ->exists();

                $logs = Activity_log::where('user_id', $request->id)->exists();

                if($reservations || $logs) {
                    $response = [
                        'error' => "The client $user->lastname $user->firstname has already been maked reservation. You can not delete it",
                    ];
                    \LogActivity::addToLog("Fail to delete client $user->lastname $user->firstname. error: He has maked reservation.");
                    return response($response, 422);
                } else {
                    $user->delete();
                    $response = [
                        'message' => "Client $user->lastname $user->firstname successfully deleted",
                    ];

                    \LogActivity::addToLog("Client  $user->lastname $user->firstname deleted");
                    return response($response, 201);
                }
            }
        }
        abort(403);
    }
}
