<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Carbon\Carbon;

class UpdateLastRequestAt
{
    public function handle($request, Closure $next)
    {
        if (auth()->guard('sanctum')->check()) {
            $user = auth()->guard('sanctum')->user();

            if ($user && $user->role_id != 2) {
                $user = User::find($user->id);
                $user->last_request_at = Carbon::now();
                $user->save();
            }
        }
        return $next($request);
    }
}