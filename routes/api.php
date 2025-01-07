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

/* default routes */
Route::get("/ressources",[DefaultController::class, "getRessources"]);
Route::get("/calendar",[DefaultController::class, "getCalendar"]);
Route::get("/reservations",[DefaultController::class, "getReservations"]);

/* authenticated route: both user and admin */
Route::middleware(["auth:sanctum", "check.user.suspension"])->group(function () {
    Route::delete("/logout",[UserAccountController::class, "logout"]);
    Route::put("/profile", [UserAccountController::class, "update"]);
    Route::put("/password", [PasswordController::class, "update"]);

    Route::get("/profile/image",[UserImageController::class, "show"]);
    Route::post("/profile/image/store",[UserImageController::class, "store"]);
    Route::put("/profile/image/delete",[UserImageController::class, "destroy"]);

    /* client routes */
    Route::get("/admin/clients",[ClientController::class, "index"]);
    Route::get("/admin/client/{id}",[ClientController::class, "show"]);
    Route::post("/admin/client/store",[ClientController::class, "store"]);
    Route::put("/admin/client/{id}/update",[ClientController::class, "update"]);
    Route::put("/admin/client/{id}/delete", [ClientController::class, "destroy"]);

    /* staff routes */
    Route::get("/admin/staff",[StaffController::class, "index"]);
    Route::get("/admin/staff/{id}",[StaffController::class, "show"]);
    Route::post("/admin/staff/store",[StaffController::class, "store"]);
    Route::put("/admin/staff/{id}/update",[StaffController::class, "update"]);
    Route::put("/admin/staff/{id}/delete", [StaffController::class, "destroy"]);
    Route::put("/admin/staff/{id}/suspend", [StaffController::class, "suspend"]);

    Route::get("/admin/authenticated-user", [UserAccountController::class, "show"]);

    /* log activity routes */
    Route::get("/admin/logs", [ActivityLogController::class, "index"]);
    Route::put("/admin/clear_logs", [ActivityLogController::class, "destroy"]);

    /* role */
    Route::get("/admin/roles", [RoleController::class, "index"]);
    Route::get("/admin/role/{id}", [RoleController::class, "show"]);
    Route::put("/admin/role/{id}/update", [RoleController::class, "update"]);

    /* permission */
    Route::get("/admin/permissions", [PermissionController::class, "index"]);

    /* option */
    Route::get("/admin/options", [OptionController::class, "index"]);
    Route::post("/admin/option/store", [OptionController::class, "store"]);
    Route::put("/admin/option/{id}/update",[OptionController::class, "update"]);
    Route::post("/admin/option/holidays/store", [OptionController::class, "save_holidays"]);

    /* agency routes */
    Route::get("/admin/agencies",[AgencyController::class, "index"]);
    Route::get("/admin/agency/{id}",[AgencyController::class, "show"]);
    Route::post("/admin/agency/store",[AgencyController::class, "store"]);
    Route::put("/admin/agency/{id}/update",[AgencyController::class, "update"]);
    Route::put("/admin/agency/{id}/delete", [AgencyController::class, "destroy"]);
    Route::put("/admin/agency/{id}/suspend", [AgencyController::class, "suspend"]);

    /* space routes */
    Route::get("/admin/spaces",[SpaceController::class, "index"]);
    Route::get("/admin/space/{id}",[SpaceController::class, "show"]);
    Route::post("/admin/space/store",[SpaceController::class, "store"]);
    Route::put("/admin/space/{id}/update", [SpaceController::class, "update"]);
    Route::put("/admin/space/{id}/delete", [SpaceController::class, "destroy"]);

    /* space images routes */
    Route::post("/admin/space/{space_id}/image/store",[SpaceImageController::class, "store"]);
    Route::put("/admin/space/image/{image_id}/delete",[SpaceImageController::class, "destroy"]);

    /* Characteristic routes */
    Route::get("/admin/characteristics",[CharacteristicController::class, "index"]);
    Route::post("/admin/characteristic/store",[CharacteristicController::class, "store"]);
    Route::put("/admin/characteristic/{id}/update",[CharacteristicController::class, "update"]);
    Route::put("/admin/characteristic/{id}/delete", [CharacteristicController::class, "destroy"]);

    /* openingdays routes */
    Route::get("/admin/openingdays",[OpeningdayController::class, "index"]);

    /* space routes */
    Route::get("/admin/ressources",[RessourceController::class, "index"]);
    Route::get("/admin/ressource/{id}",[RessourceController::class, "show"]);
    Route::post("/admin/ressource/store",[RessourceController::class, "store"]);
    Route::put("/admin/ressource/{id}/update",[RessourceController::class, "update"]);
    Route::put("/admin/ressource/{id}/delete", [RessourceController::class, "destroy"]);

    /* coupon routes */
    Route::get("/admin/coupons",[CouponController::class, "index"]);
    Route::get("/admin/coupon/{id}",[CouponController::class, "show"]);
    Route::post("/admin/coupon/store",[CouponController::class, "store"]);
    Route::put("/admin/coupon/{id}/update",[CouponController::class, "update"]);
    Route::put("/admin/coupon/{id}/delete", [CouponController::class, "destroy"]);
    Route::post("/admin/coupon/apply",[CouponController::class, "apply"]);

    /* reservation routes */
    Route::get("/admin/reservations",[ReservationController::class, "index"]);
    Route::get("/admin/reservation/{id}",[ReservationController::class, "show"]);
    Route::post("/admin/reservation/store",[ReservationController::class, "store_draft"]);
    Route::post("/admin/reservation/store/confirm",[ReservationController::class, "store"]);
    // Route::put("/admin/reservation/{id}/update",[ReservationController::class, "update"]);
    Route::put("/admin/reservation/{id}/cancel", [ReservationController::class, "cancel"]);
    Route::post("/admin/reservation/test", [ReservationController::class, "test"]);
    Route::get("/admin/calendar",[ReservationController::class, "calendar"]);

    /* payment routes */
    Route::get("/admin/payments",[PaymentController::class, "index"]);
    Route::get("/admin/payment/{id}",[PaymentController::class, "show"]);
    Route::post("/admin/payment/store",[PaymentController::class, "store"]);
    Route::put("/admin/payment/{id}/update",[PaymentController::class, "update"]);
    Route::put("/admin/payment/{id}/cancel", [PaymentController::class, "cancel"]);

    /* dashboard routes */
    Route::get("/admin/dashboard",[DashboardController::class, "index"]);

});

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    $user = $request->user();
    return $user;
});

Route::middleware("auth:sanctum")->get("/auth-permissions", function (Request $request) {
    $permissions = $request->user()->role->permissions->pluck("name")->toArray();
    return response($permissions, 201);
});