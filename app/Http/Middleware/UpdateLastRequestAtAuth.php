<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UpdateLastRequestAtAuth
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->role_id != 2) {
            $user = User::find($user->id);
            $user->last_request_at = Carbon::now();
            $user->save();
        }
        return $next($request);
    }
}