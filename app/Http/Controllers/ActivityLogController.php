<?php

namespace App\Http\Controllers;

use App\Models\Activity_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->user()->hasPermission('view_logactivity')) {
            abort(403);
        }
        $logs = Activity_log::join('users', 'users.id', '=', 'activity_logs.user_id')
                                ->select('activity_logs.*', 'users.firstname as firstname', 'users.lastname as lastname')
                                ->orderByDesc('created_at')
                                ->get();
                                
        // $logs = User::withRole()->get()->where('role_id', 2);
        return response()->json($logs, 201);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \LogActivity::addToLog($request->description);
        $response = [
            'message' => "log insert successfully.",
        ];
        return response($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $authuser = $request->user();
        if(!$authuser->hasPermission('delete_logactivity')) {
            abort(403);
        }
        if (! Hash::check($request->password, $authuser->password)) {
            $response = [
                'password' => 'Wrong password.'
            ];
            return response($response, 422);
        }
        Activity_log::truncate();
        \LogActivity::addToLog('Clearing activity logs.');
        $response = [
            'message' => 'Activity logs cleared.'
        ];
        return response($response, 201);
    }
}
