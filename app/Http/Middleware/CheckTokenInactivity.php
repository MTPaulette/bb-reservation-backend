<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckTokenInactivity
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user) {
            $now = Carbon::now(); // ->format("Y-m-d H:i");
            $lastRequest = Carbon::parse($user->last_request_at);

            if ($lastRequest && $lastRequest->diffInMinutes($now) > 15) {
                $user->tokens()->delete();
            }
        }
        return $next($request);
    }
}