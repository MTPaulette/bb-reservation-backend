<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    /*
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->user()->hasPermission('show_all_client')) {
            abort(403);
        }
        $all_clients = User::withRole()->get()->where('role_id', 2);
        return response()->json($all_clients, 201);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $authUser = $request->user();
        if( $authUser->hasPermission('view_client') || $user->id == $authUser->id ){
            // $user = User::withRole()->get()->findOrFail($request->id);
            return response()->json($user, 201);
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
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phonenumber' => 'string|min:9',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("User creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 500);
        }

        $user = User::create($validator->validated());

        $user = User::where('email', $request->email)->first();

        if($request->has('role_id')) {
            $user->role_id = $request->role_id;
        } else {
            $user->role_id = 2; //2 for role client
        }

        $user->created_by = $request->user()->id;
        $user->save();
        $response = [
            'message' => "The user $user->firstname account successfully created",
        ];

        \LogActivity::addToLog("New user created.<br/> User name: $user->lastname $user->firstname");

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
        if(!$request->user()->hasPermission('edit_client')) {
            abort(403);
        }

        $validator = Validator::make($request->all(),[
            'lastname' => 'string|max:50',
            'firstname' => 'string|max:50',
            'phonenumber' => 'string|min:9',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Fail to update user's informations. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 500);
        }
        $user = User::withRole()->findOrFail($request->id);

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
        \LogActivity::addToLog("The user $user->lastname $user->firstname has been updated.");
        return response($user, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!$request->user()->hasPermission('delete_client')) {
            abort(403);
        }

        $authUser = $request->user();
        $user = User::findOrFail($request->id);

        if (! Hash::check($request->password, $authUser->password)) {
            $response = [
                'password' => 'Wrong password.'
            ];
            \LogActivity::addToLog("Fail to delete user $user->lastname $user->firstname. error: Wrong password");
            return response($response, 422);
        }

        // check if the user has already been make reservation
        $reservations = Reservation::where('created_by', $request->id)
                            ->orWhere('receiver_user_id', $request->id)
                            ->orWhere('giver_user_id', $request->id)
                            ->exists();

        if($reservations) {
            $response = [
                'error' => "The user $user->lastname $user->firstname has already been maked reservation. You can not delete it",
            ];
            \LogActivity::addToLog("Fail to delete user $user->lastname $user->firstname. error: He has maked reservation.");
            return response($response, 422);
        } else {
            $user->delete();
            $response = [
                'message' => "User $user->lastname $user->firstname successfully deleted",
            ];

            \LogActivity::addToLog("User  $user->lastname $user->firstname deleted");
            return response($response, 201);
        }
    }
}
