<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckTokenInactivity
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->role_id != 2) {
            $now = Carbon::now();
            $lastRequest = Carbon::parse($user->last_request_at);

            //if ($lastRequest && $lastRequest->diffInSeconds($now) > 30) {
            if ($lastRequest && $lastRequest->diffInMinutes($now) > 30) {
                $user = User::find($user->id);
                $user->tokens()->delete();
                abort(401);
            }
        }
        return $next($request);
    }
}