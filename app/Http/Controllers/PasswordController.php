<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'current_password' => ['required','string'],
            'password' => ['required', 'string', 'min:8', 'max:50']
        ]);
        
        $currentPasswordHash= Hash::check($request->current_password, $user->password);
        if($currentPasswordHash){
            $user->password = $request->password;
            $user->update();
            // $token = $user->createToken('bb-reservation-syst-token')->plainTextToken;
            $response = [
                // 'token' => $token,
                'message' => "Password Updated Successfully"
            ];
            \LogActivity::addToLog("User $user->lastname $user->firstname update his password.");
            return response($response, 201);

        }else{
            $response = [
                'errors' => 'Current Password does not match with Old Password',
            ];
            \LogActivity::addToLog("User password update failed. User name: $user->lastname $user->firstname. | error: " .$response['errors']);
            return response($response, 422);
        }
    }

    public function sendResetLinkEmail(Request $request) {
        //$request->validate(['email' => 'required|email']);
    
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:250'
        ]);

        if($validator->fails()){
            return response([
                'errors' => $validator->errors(),
            ], 500);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status == Password::RESET_LINK_SENT) {
        $response = [
            'message' => [__($status)],
        ];
        return response($response, 201);
        } else {
            $response = [
                'errors' => [__($status)],
            ];
            return response($response, 500);
        }
    }
    
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token' => 'required',
            'email' => 'required|email|max:250',
            'password' => 'required|string|min:6|max:250',
        ]);

        if($validator->fails()){
            return response([
                'errors' => $validator->errors(),
            ], 500);
        }

        $user = null;

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->password = $password;
                $user->save();
                //event(new PasswordReset($user));
            }
        );
     
        if($status === Password::PASSWORD_RESET) {
            $response = [
                'user' => $user,
                'message' => [__($status)],
            ];
            return response($response, 201);
        } else {
            $response = [
                'errors' => [__($status)],
            ];
            return response($response, 500);
        }
    }
}
