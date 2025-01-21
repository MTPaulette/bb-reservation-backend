<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserSuspension
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->status == "suspended") {
            $user = Auth::user();
            $user = User::find($user->id);
            $user->tokens()->delete();
            return response([
                'errors' => [
                    'en' => "Suspended account.",
                    'fr' => "Compte suspendu.",
                ]
            ], 423);
        }

        return $next($request);
    }
}
