<?php

use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\CharacteristicController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OpeningdayController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\SpaceImageController;
use App\Http\Controllers\UserImageController;

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

    Route::get("/profile/image",[UserImageController::class, "show"]);
    Route::post("/profile/image/store",[UserImageController::class, "store"]);
    Route::put("/profile/image/delete",[UserImageController::class, "destroy"]);

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
    Route::get('/logs', [ActivityLogController::class, 'index']);
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
    Route::put("/option/{id}/update",[OptionController::class, "update"]);
    Route::post('/option/holidays/store', [OptionController::class, 'save_holidays']);

    /* agency routes */
    Route::get("/agencies",[AgencyController::class, "index"]);
    Route::get("/agency/{id}",[AgencyController::class, "show"]);
    Route::post("/agency/store",[AgencyController::class, "store"]);
    Route::put("/agency/{id}/update",[AgencyController::class, "update"]);
    Route::put('/agency/{id}/delete', [AgencyController::class, 'destroy']);
    Route::put('/agency/{id}/suspend', [AgencyController::class, 'suspend']);

    /* space routes */
    Route::get("/spaces",[SpaceController::class, "index"]);
    Route::get("/space/{id}",[SpaceController::class, "show"]);
    Route::post("/space/store",[SpaceController::class, "store"]);
    Route::put('/space/{id}/delete', [SpaceController::class, 'destroy']);

    /* space images routes */
    Route::post("/space/{space_id}/image/store",[SpaceImageController::class, "store"]);
    Route::put("/space/image/{image_id}/delete",[SpaceImageController::class, "destroy"]);

    /* Characteristic routes */
    Route::get("/characteristics",[CharacteristicController::class, "index"]);
    Route::post("/characteristic/store",[CharacteristicController::class, "store"]);
    Route::put("/characteristic/{id}/update",[CharacteristicController::class, "update"]);
    Route::put('/characteristic/{id}/delete', [CharacteristicController::class, 'destroy']);

    /* openingdays routes */
    Route::get("/openingdays",[OpeningdayController::class, "index"]);

    /* space routes */
    Route::get("/ressources",[RessourceController::class, "index"]);
    Route::get("/ressource/{id}",[RessourceController::class, "show"]);
    Route::post("/ressource/store",[RessourceController::class, "store"]);
    Route::put("/ressource/{id}/update",[RessourceController::class, "update"]);
    Route::put('/ressource/{id}/delete', [RessourceController::class, 'destroy']);

    /* coupon routes */
    Route::get("/coupons",[CouponController::class, "index"]);
    Route::get("/coupon/{id}",[CouponController::class, "show"]);
    Route::post("/coupon/store",[CouponController::class, "store"]);
    Route::put("/coupon/{id}/update",[CouponController::class, "update"]);
    Route::put('/coupon/{id}/delete', [CouponController::class, 'destroy']);


});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();
    return $user;
});

Route::middleware('auth:sanctum')->get('/auth-permissions', function (Request $request) {
    $permissions = $request->user()->role->permissions->pluck('name')->toArray();
    return response($permissions, 201);
});