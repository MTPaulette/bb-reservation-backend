<?php

use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserAccountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* unauthenticated route */
Route::post("/login",[UserAccountController::class, "login"]);
Route::post("/register",[UserAccountController::class, "register"]);


/* authenticated route: both user and admin */
Route::middleware('auth:sanctum')->group(function () {
    Route::get("/clients",[ClientController::class, "index"]);
    Route::get("/client/{user}",[ClientController::class, "show"]);
    Route::post("/client/store",[ClientController::class, "store"]);
    Route::put("/client/{id}/update",[ClientController::class, "update"]);
    Route::put('/client/{id}/delete', [ClientController::class, 'destroy']);
    // Route::put('/client/{id}/delete', [ClientController::class, 'destroy']);

    Route::delete("/logout",[UserAccountController::class, "logout"]);
    Route::put('/profile', [UserAccountController::class, 'update']);
    Route::put('/password', [PasswordController::class, 'update']);

    Route::get('/authenticated-user', [UserAccountController::class, 'show'])->name('auth.user');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
