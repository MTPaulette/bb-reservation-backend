<?php

namespace App\Http\Controllers;

use App\Models\Activity_log;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
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

    public function show(Request $request)
    {
        $authUser = $request->user();
        if( $authUser->hasPermission('view_client') || $request->id == $authUser->id ){
            $user = User::withRole()->findOrFail($request->id);
            if($user->role == 'client') {
                return response()->json($user, 201);
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
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phonenumber' => 'string|min:9',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Client creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 500);
        }

        $user = User::create($validator->validated());

        $user = User::where('email', $request->email)->first();
        $user->role_id = 2;
        $user->created_by = $request->user()->id;
        $user->save();
        $response = [
            'message' => "The client $user->firstname account successfully created",
        ];

        \LogActivity::addToLog("New client created.<br/> client name: $user->lastname $user->firstname");

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
                'phonenumber' => 'string|min:9',
            ]);

            if($validator->fails()){
                \LogActivity::addToLog("Fail to update client's informations. ".$validator->errors());
                return response([
                    'errors' => $validator->errors(),
                ], 500);
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
