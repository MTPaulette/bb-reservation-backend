<?php

use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\OpeningdayController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;

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
Route::middleware(['auth:sanctum', 'check.user.suspension'])->group(function () {
    Route::delete("/logout",[UserAccountController::class, "logout"]);
    Route::put('/profile', [UserAccountController::class, 'update']);
    Route::put('/password', [PasswordController::class, 'update']);

    /* client routes */
    Route::get("/clients",[ClientController::class, "index"]);
    Route::get("/client/{id}",[ClientController::class, "show"]);
    Route::post("/client/store",[ClientController::class, "store"]);
    Route::put("/client/{id}/update",[ClientController::class, "update"]);
    Route::put('/client/{id}/delete', [ClientController::class, 'destroy']);

    /* staff routes */
    Route::get("/staff",[StaffController::class, "index"]);
    Route::get("/staff/{id}",[StaffController::class, "show"]);
    Route::post("/staff/store",[StaffController::class, "store"]);
    Route::put("/staff/{id}/update",[StaffController::class, "update"]);
    Route::put('/staff/{id}/delete', [StaffController::class, 'destroy']);
    Route::put('/staff/{id}/suspend', [StaffController::class, 'suspend']);

    Route::get('/authenticated-user', [UserAccountController::class, 'show']);

    /* log activity routes */
    Route::get('/activity_logs', [ActivityLogController::class, 'index']);
    Route::put('/clear_logs', [ActivityLogController::class, 'destroy']);

    /* role */
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/role/{id}', [RoleController::class, 'show']);
    Route::put('/role/{id}/update', [RoleController::class, 'update']);

    /* permission */
    Route::get('/permissions', [PermissionController::class, 'index']);

    /* option */
    Route::get('/options', [OptionController::class, 'index']);
    Route::post('/option/store', [OptionController::class, 'store']);

    /* agency routes */
    Route::get("/agencies",[AgencyController::class, "index"]);
    Route::get("/agency/{id}",[AgencyController::class, "show"]);
    Route::post("/agency/store",[AgencyController::class, "store"]);
    Route::put("/agency/{id}/update",[AgencyController::class, "update"]);
    Route::put('/agency/{id}/delete', [AgencyController::class, 'destroy']);
    Route::put('/agency/{id}/suspend', [AgencyController::class, 'suspend']);

    /* openingdays routes */
    Route::get("/openingdays",[OpeningdayController::class, "index"]);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/auth-permissions', function (Request $request) {
    $permissions = $request->user()->role->permissions->pluck('name')->toArray();
    return response($permissions, 201);
});