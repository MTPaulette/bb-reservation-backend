<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;


class UserAccountController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'lastname' => 'required|string|max:50',
            'firstname' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
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
            'email' => 'required|email|',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Fail to log in. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 500);
        }
    
        // $user = User::where('email', $request->email)->first();
        $user = User::withRole()->where('email', $request->email)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            \LogActivity::addToLog("Fail to log in. Current password and old password do not match.");
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }
 
        // $user->tokens()->delete();
        $token = $user->createToken('bb-reservation-syst-token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        \LogActivity::addToLog("The user $user->lastname $user->firstname logged in");
        return response($response, 201);
    }

    /**
     * Update the user's informations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        $user->update();

        \LogActivity::addToLog("The user $user->lastname $user->firstname update his profile.");
        $response = [
            'user' => $user,
            'token' => $request->bearerToken()
        ];
        return response($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        \LogActivity::addToLog("The user $user->lastname $user->firstname logged out.");
        return response([
            'message' => 'Logout user',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = $request->user();
        // $user = User::userWithAdminAndRoleName()->findOrFail($request->user()->id);
        $response = [
            'user' => $user,
        ];
        return response($response, 201);
    }
}
