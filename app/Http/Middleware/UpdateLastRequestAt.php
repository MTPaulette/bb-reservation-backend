<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UpdateLastRequestAt
{
    public function handle(Request $request, Closure $next)
    {
        // Mettre à jour la date de la dernière requête API
        $user = $request->user();
        if ($user) {
            echo "dans le middleware";
            $user->last_request_at = now();
            $user->save();
        }

        return $next($request);
    }
}
