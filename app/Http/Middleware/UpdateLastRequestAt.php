<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UpdateLastRequestAt
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user) {
            $user->last_request_at = Carbon::now();
            $user->save();
        }
        return $next($request);
    }
}