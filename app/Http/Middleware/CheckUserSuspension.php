<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserSuspension
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->status == "suspended") {
            // L'utilisateur est suspendu, on retourne une réponse JSON
            // abort(423);
            return response()->json([
                'error' => 'Votre compte est suspendu.',
                'status' => 423,
            ], 423);
        }

        return $next($request);
    }
}
