<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request;

class CheckTokenInactivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle(Request $request)
    {
        // Récupérer la date de la dernière requête API
        $user = $request->user();
        // Vérifie si la dernière requête API a été faite il y a plus de 15 minutes
        // if ($lastRequest && (now()->diffInMinutes($lastRequest) > 15)) {
        if ($user) {
          if ($user->role_id != 2) {
            $lastRequest = $user->last_request_at;
            if ($lastRequest && (now()->diffInSeconds($lastRequest) > 1)) {
              $user->tokens()->delete();
            }
          }
        }
    }
}
