<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class CheckTokenInactivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Récupérer la date de la dernière requête API
        $lastRequest = Auth::user()->last_request_at;

        // Vérifie si la dernière requête API a été faite il y a plus de 15 minutes
        if ($lastRequest && (now()->diffInMinutes($lastRequest) > 15)) {
            // Expire le token
            Auth::logout();
        }
        
      // $user = Auth::user();
      // $user->tokens()->delete();
    }
}
