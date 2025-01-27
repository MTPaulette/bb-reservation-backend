<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Activity_log;
use App\Models\Agency;
use App\Models\Coupon;
use App\Models\Reservation;
use App\Models\Ressource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
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

    public function index(Request $request)
    {
        $user = $request->user();
        if(
            !$user->hasPermission('show_all_admin') && 
            !$user->hasPermission('show_all_admin_of_agency') &&
            !$user->hasPermission('show_all_superadmin')
        ) {
            abort(403);
        }

        if($user->hasPermission('show_all_superadmin')) {
            $all_staffs = User::withAgencyAndRole()
                                ->where('roles.name', 'superadmin')
                                ->OrWhere('roles.name', 'admin')
                                ->get();
            return response()->json($all_staffs, 201);
        }

        if($user->hasPermission('show_all_admin')) {
            $all_staffs = User::withAgencyAndRole()
                                ->where('roles.name', 'admin')
                                ->get();
            return response()->json($all_staffs, 201);
        }

        if($user->hasPermission('show_all_admin_of_agency')) {
            $all_staffs = User::withAgencyAndRole()
                                ->where('roles.name', 'admin')
                                ->where('users.work_at', $user->work_at)
                                ->get();
            return response()->json($all_staffs, 201);
        }
    }

    public function show(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('view_admin') ||
            $authUser->hasPermission('view_admin_of_agency') ||
            $authUser->hasPermission('view_superadmin')
        ) {

            $user = User::withRole()->findOrFail($request->id);
            if($authUser->hasPermission('view_superadmin')) {
                if($user->role != 'superadmin' && $user->role != 'admin') {
                    abort(403);
                }
            } else {
                if($authUser->hasPermission('view_admin')) {
                    if($user->role != 'admin') {
                        abort(403);
                    }
                } else {
                    if($authUser->hasPermission('view_admin_of_agency')) {
                        if($user->role != 'admin' || $authUser->work_at != $user->work_at) {
                            abort(403);
                        }
                    }
                } 
            }

            $coupons = Coupon::with([
                                'createdBy' => function($query) {
                                    $query->select('id', 'lastname', 'firstname');
                                }
                            ])
                            ->withCount('users')
                            ->where('coupons.created_by', $request->id)->get();
            $ressources = Ressource::withAgencySpaceUser()
                                    ->where('ressources.created_by', $request->id)
                                    ->get();
            
            $user = $this->userAllInformations()->findOrFail($request->id);
            $created_clients = User::withRole()
                                    ->where('users.created_by', $request->id)
                                    ->where('roles.name', 'client')
                                    ->get()
                                    ->toArray();
            $created_staff = User::withAgencyAndRole()
                                    ->where('roles.name', 'admin')
                                    // ->OrWhere('roles.name', 'admin')
                                    ->where('users.created_by', '=', $request->id)
                                    ->get();
            
            $reservations_results =
                Reservation::where('reservations.created_by', '=', $request->id)
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
                'totalCoupons' => $coupons->count(),
                'totalRessources' => $ressources->count(),
                'totalReservations' => sizeof($reservations),
                'totalCreatedClients' => sizeof($created_clients),
                'totalCreatedStaff' => $created_staff->count(),
                'user' => $user,
                'coupons' => $coupons,
                'ressources' => $ressources,
                'reservations' => $reservations,
                'createdClients' => $created_clients,
                'createdStaff' => $created_staff,
            ];
            return response()->json($response, 201);
        }
        abort(403);
    }

    public function store(Request $request)
    {
        if(
            !$request->user()->hasPermission('create_superadmin') &&
            !$request->user()->hasPermission('create_admin')
        ) {
            abort(403);
        }
        $validator = Validator::make($request->all(),[
            'lastname' => 'required|string|max:250',
            'firstname' => 'required|string|max:250',
            'email' => 'required|email|unique:users|max:250',
            'password' => 'required|string|min:8|max:50',
            // 'phonenumber' => 'required|string|size:12',
            'phonenumber' => ['required', 'integer', 'regex:/^(2[0-9]{2}[6](2|5|6|7|8|9)[0-9]{7})$/'],
            'language' => 'required|string|in:en,fr',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Admin/superadmin creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $agency = Agency::findOrFail($request->agency_id);
        if($agency->status == 'suspended') {
            return response([
                'errors' => [
                    'en' => "Suspended Agency $agency->name.",
                    'fr' => "Agence $agency->name suspendue.",
                ]
            ], 424);
        }

        $role = Role::findOrFail($request->role_id);
    
        $user = User::create($validator->validated());

        $user = User::where('email', $request->email)->first();
        $usertype = '';
        if($request->user()->hasPermission('create_admin')) {
            if($request->user()->hasPermission('create_superadmin')) {
                $user->role_id = $role->id ? $role->id : 1;
                $user->work_at = $agency->id ? $agency->id : 1;
                $usertype = $role->id == 1 ? 'admin' : 'superadmin';
            } else {
                $user->role_id = 1;
                $user->work_at = $agency->id ? $agency->id : 1;
                $usertype = 'admin';
            }
        }


        if($request->user()->hasPermission('create_superadmin')) {
            if($request->user()->hasPermission('create_admin')) {
                $user->role_id = $role->id ? $role->id : 1;
                $user->work_at = $agency->id ? $agency->id : 1;
                $usertype = $role->id == 1 ? 'admin' : 'superadmin';
            } else {
                $user->role_id = 3;
                $user->work_at = $agency->id ? $agency->id : 1;
                $usertype = 'superadmin';
            }
        }

        $user->created_by = $request->user()->id;
        $user->save();
        $response = [
            'message' => "The $usertype $user->firstname account successfully created",
        ];

        \LogActivity::addToLog("New $usertype created. $usertype name: $user->lastname $user->firstname");

        return response($response, 201);
    }

    public function update(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('edit_admin') ||
            $authUser->hasPermission('edit_superadmin')
        ) {
            $validator = Validator::make($request->all(),[
                'lastname' => 'string|max:50',
                'firstname' => 'string|max:50',
                'phonenumber' => ['integer', 'regex:/^(2[0-9]{2}[6](2|5|6|7|8|9)[0-9]{7})$/'],
                // 'phonenumber' => 'string|size:12',
                'language' => 'string|nullable|in:en,fr',
            ]);

            if($validator->fails()){
                \LogActivity::addToLog("Fail to update client's informations. ".$validator->errors());
                return response([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = User::withRole()->findOrFail($request->id);
            if( $authUser->hasPermission('edit_admin')){
                if($user->role == 'admin') {
                    if($request->has('agency_id') && isset($request->agency_id)) {
                        $agency = Agency::findOrFail($request->agency_id);
                        if($agency->status == 'suspended') {
                            return response([
                                'errors' => [
                                    'en' => "Suspended Agency $agency->name.",
                                    'fr' => "Agence $agency->name suspendue.",
                                ]
                            ], 424);
                        }
                        $user->work_at = $agency->id;
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
                    }
                    /*
                    if($request->has('role_id') && isset($request->role_id)) {
                        $role = Role::findOrFail($request->role_id);
                        $user->role_id = $role->id;
                    }*/
                }
            }
            if( $authUser->hasPermission('edit_superadmin')){
                if($user->role == 'superadmin') {
                    if($request->has('agency_id') && isset($request->agency_id)) {
                        $agency = Agency::findOrFail($request->agency_id);
                        if($agency->status == 'suspended') {
                            return response([
                                'errors' => [
                                    'en' => "Suspended Agency $agency->name.",
                                    'fr' => "Agence $agency->name suspendue.",
                                ]
                            ], 424);
                        }
                        $user->work_at = $agency->id;
                    }
                    if($request->has('role_id') && isset($request->role_id)) {
                        $role = Role::findOrFail($request->role_id);
                        $user->role_id = $role->id;
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
                }
            }

            $user->update();
            \LogActivity::addToLog("The $user->role $user->lastname $user->firstname has been updated.");
            return response($user, 201);
        }
        abort(403);
    }

    public function destroy(Request $request)
    {
        if(
            $request->user()->hasPermission('delete_admin') ||
            $request->user()->hasPermission('delete_superadmin')
        ) {
            $authUser = $request->user();
            $user = User::withRole()->findOrFail($request->id);
            if($user->role == 'admin') {
                if (! Hash::check($request->password, $authUser->password)) {
                    $response = [
                        'errors' => [
                            'en' => "Wrong password.",
                            'fr' => "Mauvais mot de passe",
                        ]
                    ];
                    \LogActivity::addToLog("Fail to delete $user->role $user->lastname $user->firstname. error: Wrong password");
                    return response($response, 422);
                }

                // check if the user has already been make reservation
                $reservations = Reservation::where('created_by', $request->id)
                                    ->orWhere('cancelled_by', $request->id)
                                    ->orWhere('client_id', $request->id)
                                    ->exists();

                $logs = Activity_log::where('user_id', $request->id)->exists();

                if($reservations || $logs) {
                    $response = [
                        'en' => "The $user->role $user->lastname $user->firstname has already been maked reservation. You can not delete it",
                        'fr' => "Le  $user->role $user->lastname $user->firstname a deja effectue une reservation. Vous ne pouvez pas le supprimer",
                    ];
                    \LogActivity::addToLog("Fail to delete $user->role $user->lastname $user->firstname. error: He has maked reservation.");
                    return response($response, 422);
                } else {
                    $user->delete();
                    $response = [
                        'message' => "The $user->role $user->lastname $user->firstname successfully deleted",
                    ];

                    \LogActivity::addToLog("The $user->role  $user->lastname $user->firstname deleted");
                    return response($response, 201);
                }
            }
        }
        abort(403);
    }

    public function suspend(Request $request)
    {
        $authUser = $request->user();
        if($authUser->hasPermission('suspend_staff')) {
            $user = User::withRole()->findOrFail($request->id);
            if (! Hash::check($request->password, $authUser->password)) {
                $response = [
                    'password' => 'Wrong password.'
                ];
                \LogActivity::addToLog("Fail to suspend $user->role $user->lastname $user->firstname. error: Wrong password");
                return response($response, 422);
            }
            if($user->role == 'superadmin' || $user->role == 'admin') {
                if($request->cancel_suspension){
                    $user->status = 'active';
                    $response = [
                        'message' => "The $user->role $user->lastname $user->firstname's suspension is stopped",
                    ];
                } else {
                    $validator = Validator::make($request->all(),[
                        'reason_for_suspension_en' => 'required|string|max:250',
                        'reason_for_suspension_fr' => 'required|string|max:250',
                    ]);
        
                    if($validator->fails()){
                        \LogActivity::addToLog("The $user->role $user->lastname $user->firstname  suspension failed. ".$validator->errors());
                        return response([
                            'errors' => $validator->errors(),
                        ], 422);
                    }
                    $user->status = 'suspended';
                    $user->reason_for_suspension_en = $request->reason_for_suspension_en;
                    $user->reason_for_suspension_fr = $request->reason_for_suspension_fr;
                    $user->suspended_by = $authUser->id;
                    $user->suspended_at = now();
                    $response = [
                        'message' => "The $user->role $user->lastname $user->firstname successfully suspended",
                    ];
                }
                $user->save();

                \LogActivity::addToLog("The $user->role $user->lastname $user->firstname status updated");
                return response($response, 201);
            }
        }
        abort(403);
    }
}