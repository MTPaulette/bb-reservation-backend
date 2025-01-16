<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;


class UserAccountController extends Controller
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
            'role' => function($query) {
                $query->select('id', 'name');
            },
            'workAt'=> function($query) {
                $query->select('id', 'name');
            }
        ]);
    }
    
    public function reservationsQuery($id)
    {
        return
        Reservation::where('reservations.client_id', '=', $id)
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
        ->orderByDesc('reservations.created_at');
        // ->get();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'lastname' => 'required|string|max:250',
            'firstname' => 'required|string|max:250',
            'email' => 'required|email|unique:users|max:250',
            'password' => 'required|string|min:6|max:50',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Fail to register the user. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 500);
        }

        $user = User::create($validator->validated());

        $user = User::where('email', $request->email)->first();

        if($request->role_id == 1) {
            $user->role_id = 1;
            $user->save();

        } else if($request->role_id == 3) {
            $user->role_id = 3;
            $user->save();
        }
        \LogActivity::addToLog("New user created. User name: $user->lastname $user->firstname");
        return response($user, 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:250',
            'password' => 'required|string|min:6|max:50',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Fail to log in. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 500);
        }
    
        // $user = User::where('email', $request->email)->first();
        $user = User::withRole()->where('users.email', $request->email)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            \LogActivity::addToLog("Fail to log in. Current password and old password do not match.");
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if($user->status == 'suspended') {
            return response([
                'errors' => [
                    'en' => "Suspended account.",
                    'fr' => "Compte suspendu.",
                ]
            ], 423);
        }
 
        // $user->tokens()->delete();
        $token = $user->createToken('bb-reservation-syst-token')->plainTextToken;
        $logged_user = User::find($user->id);
        $permissions = $logged_user->role->permissions->pluck('name')->toArray();

        if($user->role_id != 2) {
            $user->last_request_at = Carbon::now();
            $user->save();
        }

        $response = [
            'user' => $user,
            'token' => $token,
            'permissions' => $permissions,
        ];

        \LogActivity::addToLog("The user $user->lastname $user->firstname logged in");
        return response($response, 201);
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($request->user()->id),
            ],
        ]);
        $validator = Validator::make($request->all(),[
            'lastname' => 'string|max:250',
            'firstname' => 'string|max:250',
            'phonenumber' => ['integer', 'regex:/^(2[0-9]{2}[6](2|5|6|7|8|9)[0-9]{7})$/'],
            'language' => 'string|nullable|in:en,fr',
        ]);
        if($validator->fails()){
            \LogActivity::addToLog("Fail to update user's informations. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 500);
        }
        $user = $request->user();

        if($request->has('email') && isset($request->email)) {
            $user->email = $request->email;
        }

        if($request->has('lastname') && isset($request->lastname)) {
            $user->lastname = $request->lastname;
        }

        if($request->has('firstname') && isset($request->firstname)) {
            $user->firstname = $request->firstname;
        }

        if($request->has('phonenumber') && isset($request->phonenumber)) {
            $user->phonenumber = $request->phonenumber;
        }

        if($request->has('language') && isset($request->language)) {
            $user->language = $request->language;
        }

        $user->update();

        \LogActivity::addToLog("The user $user->lastname $user->firstname update his profile.");
        $response = [
            'user' => $user,
            'token' => $request->bearerToken()
        ];
        return response($response, 201);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if($user->role_id != 2) {
            $user->last_request_at = Carbon::now();
            $user->save();
        }
    
        $user->tokens()->delete();
        \LogActivity::addToLog("The user $user->lastname $user->firstname logged out.");
        return response([
            'message' => 'Logout user',
        ], 201);
    }

    public function show(Request $request)
    {
        $user = $request->user();
        if($user) {
            $user = $this->userAllInformations()->findOrFail($user->id);

            $reservations = [];
            $reservations_results = $this->reservationsQuery($user->id)->whereNot('reservations.state', 'cancelled')->get();
            foreach ($reservations_results as $reservation) {
                array_push($reservations, $reservation);
            };

            $cancelledReservations = [];
            $reservations_cancelled_results = $this->reservationsQuery($user->id)->where('reservations.state', 'cancelled')->get();
            foreach ($reservations_cancelled_results as $reservation) {
                array_push($cancelledReservations, $reservation);
            };

            $response = [
                'totalReservations' => sizeof($reservations),
                'totalCancelledReservations' => sizeof($cancelledReservations),
                'totalCoupons' => $user->coupons->count(),
                'user' => $user,
                'coupons' => $user->coupons,
                'reservations' => $reservations,
                'cancelledReservations' => $cancelledReservations,
            ];
            return response($response, 201);
        }
        abort(404);
    }
}

