<?php

namespace App\Http\Controllers;

use App\Models\Activity_log;
use App\Models\Agency;
use App\Models\Reservation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
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
    
    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('view_admin') ||
            $authUser->hasPermission('view_admin_of_agency') ||
            $authUser->hasPermission('view_superadmin')
        ) {

            if( $authUser->hasPermission('view_superadmin')){
                $user = User::withRole()->findOrFail($request->id);
                if($user->role == 'superadmin') {
                    $user = User::withAgencyAndRole()
                                    ->where('users.id', $request->id)
                                    ->get();
                    return response()->json($user, 201);
                }
            }

            if( $authUser->hasPermission('view_admin')){
                $user = User::withRole()->findOrFail($request->id);
                if($user->role == 'admin' && $user->work_at == $user->work_at) {
                    $user = User::withAgencyAndRole()
                                    ->where('users.id', $request->id)
                                    ->get();
                    return response()->json($user, 201);
                }
            }

            if( $authUser->hasPermission('view_admin_of_agency')){
                $user = User::withRole()->findOrFail($request->id);
                if($user->role == 'admin' && $authUser->work_at == $user->work_at) {
                    $user = User::withAgencyAndRole()
                                    ->where('users.id', $request->id)
                                    ->get();
                    return response()->json($user, 201);
                }
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
            'phonenumber' => 'string|min:9|max:250',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Admin/superadmin creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create($validator->validated());

        $user = User::where('email', $request->email)->first();
        $agency = Agency::findOrFail($request->agency_id);
        $role = Role::findOrFail($request->role_id);

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('edit_admin') ||
            $authUser->hasPermission('edit_superadmin')
        ) {
            $user = User::withRole()->findOrFail($request->id);
            if( $authUser->hasPermission('edit_admin')){
                if($user->role == 'admin') {
                    if($request->has('agency_id') && isset($request->agency_id)) {
                        $agency = Agency::findOrFail($request->agency_id);
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
                }
            }

            $user->update();
            \LogActivity::addToLog("The $user->role $user->lastname $user->firstname has been updated.");
            return response($user, 201);
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
        if(
            $request->user()->hasPermission('delete_admin') ||
            $request->user()->hasPermission('delete_superadmin')
        ) {
            $authUser = $request->user();
            $user = User::withRole()->findOrFail($request->id);
            if($user->role == 'admin') {
                if (! Hash::check($request->password, $authUser->password)) {
                    $response = [
                        'password' => 'Wrong password.'
                    ];
                    \LogActivity::addToLog("Fail to delete $user->role $user->lastname $user->firstname. error: Wrong password");
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
                        'error' => "The $user->role $user->lastname $user->firstname has already been maked reservation. You can not delete it",
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
                    $user->status = 'suspended';
                    $response = [
                        'message' => "The $user->role $user->lastname $user->firstname successfully suspended",
                    ];
                }
                $user->save();

                \LogActivity::addToLog("The $user->role  $user->lastname $user->firstname status updated");
                return response($response, 201);
            }
        }
        abort(403);
    }

}
