<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\PasswordController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\CharacteristicController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OpeningdayController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\RessourceController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SpaceController;
use App\Http\Controllers\Admin\SpaceImageController;
use App\Http\Controllers\DefaultController;
use App\Http\Controllers\UserAccountController;
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

/* reset password routes */
Route::post('/forgot-password', [PasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [PasswordController::class, 'reset']);


Route::middleware(["update.last_request_at"])->group(function () {
    /* default routes */
    Route::get("/ressources",[DefaultController::class, "getRessources"]);
    Route::get("/calendar",[DefaultController::class, "getCalendar"]);
    Route::get("/reservations",[DefaultController::class, "getReservations"]);
    Route::get("/agencies",[DefaultController::class, "getAgencies"]);
});



Route::middleware(["auth:sanctum", "check.token.inactivity", "update.last_request_at.auth"])->group(function () {
    Route::delete("/logout",[UserAccountController::class, "logout"]);
});



/* authenticated route: both user and admin */
Route::middleware(["auth:sanctum", "check.user.suspension", "check.token.inactivity", "update.last_request_at.auth"])->group(function () {
    // Route::delete("/logout",[UserAccountController::class, "logout"]);
    Route::get("/profile", [UserAccountController::class, "show"]);
    Route::put("/profile", [UserAccountController::class, "update"]);
    Route::put("/password", [PasswordController::class, "update"]);

    Route::get("/profile/image",[UserImageController::class, "show"]);
    Route::post("/profile/image/store",[UserImageController::class, "store"]);
    Route::put("/profile/image/delete",[UserImageController::class, "destroy"]);

    Route::prefix('admin')->group(function () {

        /* client routes */
        Route::get("/clients",[ClientController::class, "index"]);
        Route::get("/client/{id}",[ClientController::class, "show"]);
        Route::post("/client/store",[ClientController::class, "store"]);
        Route::put("/client/{id}/update",[ClientController::class, "update"]);
        Route::put("/client/{id}/delete", [ClientController::class, "destroy"]);

        /* staff routes */
        Route::get("/staff",[StaffController::class, "index"]);
        Route::get("/staff/{id}",[StaffController::class, "show"]);
        Route::post("/staff/store",[StaffController::class, "store"]);
        Route::put("/staff/{id}/update",[StaffController::class, "update"]);
        Route::put("/staff/{id}/delete", [StaffController::class, "destroy"]);
        Route::put("/staff/{id}/suspend", [StaffController::class, "suspend"]);

        // Route::get("/authenticated-user", [UserAccountController::class, "show"]);

        /* log activity routes */
        Route::get("/logs", [ActivityLogController::class, "index"]);
        Route::put("/clear_logs", [ActivityLogController::class, "destroy"]);

        /* role */
        Route::get("/roles", [RoleController::class, "index"]);
        Route::get("/role/{id}", [RoleController::class, "show"]);
        Route::put("/role/{id}/update", [RoleController::class, "update"]);

        /* permission */
        Route::get("/permissions", [PermissionController::class, "index"]);

        /* option */
        Route::get("/options", [OptionController::class, "index"]);
        Route::post("/option/store", [OptionController::class, "store"]);
        Route::put("/option/{id}/update",[OptionController::class, "update"]);
        Route::post("/option/holidays/store", [OptionController::class, "save_holidays"]);

        /* agency routes */
        Route::get("/agencies",[AgencyController::class, "index"]);
        Route::get("/agency/{id}",[AgencyController::class, "show"]);
        Route::post("/agency/store",[AgencyController::class, "store"]);
        Route::put("/agency/{id}/update",[AgencyController::class, "update"]);
        Route::put("/agency/{id}/delete", [AgencyController::class, "destroy"]);
        Route::put("/agency/{id}/suspend", [AgencyController::class, "suspend"]);

        /* space routes */
        Route::get("/spaces",[SpaceController::class, "index"]);
        Route::get("/space/{id}",[SpaceController::class, "show"]);
        Route::post("/space/store",[SpaceController::class, "store"]);
        Route::put("/space/{id}/update", [SpaceController::class, "update"]);
        Route::put("/space/{id}/delete", [SpaceController::class, "destroy"]);

        /* space images routes */
        Route::post("/space/{space_id}/image/store",[SpaceImageController::class, "store"]);
        Route::put("/space/image/{image_id}/delete",[SpaceImageController::class, "destroy"]);

        /* Characteristic routes */
        Route::get("/characteristics",[CharacteristicController::class, "index"]);
        Route::post("/characteristic/store",[CharacteristicController::class, "store"]);
        Route::put("/characteristic/{id}/update",[CharacteristicController::class, "update"]);
        Route::put("/characteristic/{id}/delete", [CharacteristicController::class, "destroy"]);

        /* openingdays routes */
        Route::get("/openingdays",[OpeningdayController::class, "index"]);

        /* space routes */
        Route::get("/ressources",[RessourceController::class, "index"]);
        Route::get("/ressource/{id}",[RessourceController::class, "show"]);
        Route::post("/ressource/store",[RessourceController::class, "store"]);
        Route::put("/ressource/{id}/update",[RessourceController::class, "update"]);
        Route::put("/ressource/{id}/delete", [RessourceController::class, "destroy"]);

        /* coupon routes */
        Route::get("/coupons",[CouponController::class, "index"]);
        Route::get("/coupon/{id}",[CouponController::class, "show"]);
        Route::post("/coupon/store",[CouponController::class, "store"]);
        Route::put("/coupon/{id}/update",[CouponController::class, "update"]);
        Route::put("/coupon/{id}/delete", [CouponController::class, "destroy"]);
        Route::post("/coupon/apply",[CouponController::class, "apply"]);

        /* reservation routes */
        Route::get("/reservations",[ReservationController::class, "index"]);
        Route::get("/reservation/{id}",[ReservationController::class, "show"]);
        Route::post("/reservation/store",[ReservationController::class, "store_draft"]);
        Route::post("/reservation/store/confirm",[ReservationController::class, "store"]);
        // Route::put("/reservation/{id}/update",[ReservationController::class, "update"]);
        Route::put("/reservation/{id}/cancel", [ReservationController::class, "cancel"]);
        Route::post("/reservation/test", [ReservationController::class, "test"]);
        Route::get("/calendar",[ReservationController::class, "calendar"]);

        /* payment routes */
        Route::get("/payments",[PaymentController::class, "index"]);
        Route::post("/payment/store",[PaymentController::class, "store"]);

        /* dashboard routes */
        Route::get("/dashboard",[DashboardController::class, "index"]);
    });

});



Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    $user = $request->user();
    return $user;
});



Route::middleware("auth:sanctum")->get("/auth-permissions", function (Request $request) {
    $permissions = $request->user()->role->permissions->pluck("name")->toArray();
    return response($permissions, 201);
});