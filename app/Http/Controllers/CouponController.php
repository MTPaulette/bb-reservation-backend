<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Reservation;
use App\Models\Coupon;
use App\Models\Space;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function couponWithCreator()
    {
        return
        Coupon::with([
            'createdBy' => function($query) {
                $query->select('id', 'lastname', 'firstname');
            }
        ]);
    }

    public function couponAllInformations()
    {
        return
        Coupon::with([
            'createdBy' => function($query) {
                $query->select('id', 'lastname', 'firstname');
            },
            'users' => function($query) {
                $query->select('id', 'lastname', 'firstname');
            }
        ]);
    }

    public function index(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('manage_coupons') ||
            $authUser->hasPermission('show_all_coupon')
        ) {
            $coupons = $this->couponAllInformations()->get();
            return response()->json($coupons, 201);
        }

        abort(403);
    }

    public function show(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('manage_coupons') ||
            $authUser->hasPermission('view_coupon')
        ) {
            $coupon = $this->couponWithCreator()->findOrFail($request->id);
            return response()->json($coupon, 201);
        }

        abort(403);
    }

    public function store(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_coupons') &&
            !$authUser->hasPermission('create_coupon')
        ) {
            abort(403);
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|unique:coupons|max:250',
            'total_usage' => 'required|integer|min:1',
            'percent' => 'nullable|integer|min:1',
            'amount' => 'nullable|integer|min:1',
            'expired_on' => 'required|date|after:'.Carbon::today()->format('Y-m-d'), //after, before
            'note_en' => 'string|nullable',
            'note_fr' => 'string|nullable',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Coupon creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }
        $code = Str::random(9);
        while(Coupon::where('code', $code)->exists()) {
            $code = Str::random(9);
        };
        $coupon = Coupon::create($validator->validated());
        $coupon->created_by = $authUser->id;
        $coupon->code = $code;
        $coupon->save();

        $response = [
            'message' => "The coupon $coupon->name successfully created",
        ];

        \LogActivity::addToLog("New coupon created. coupon name: $coupon->name");
        return response($response, 201);
    }

    public function update(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_coupons') &&
            !$authUser->hasPermission('edit_coupon') &&
            !$authUser->hasPermission('edit_coupon_of_agency')
        ) {
            abort(403);
        }
        $validator = Validator::make($request->all(),[
            'quantity' => 'required|integer|min:1',
            'price_hour' => 'required|integer|min:1',
            'price_midday' => 'required|integer|min:1',
            'price_day' => 'required|integer|min:1',
            'price_week' => 'required|integer|min:1',
            'price_month' => 'required|integer|min:1',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Coupon updation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $agency = Agency::findOrFail($request->agency_id);
        $space = Space::findOrFail($request->space_id);
        $coupon = Coupon::findOrFail($request->id);

        $existing_coupon = Coupon::where('agency_id', $agency->id)
        ->where('space_id', $space->id)->first();

        if($authUser->hasPermission('edit_coupon_of_agency')) {
            if(
                $agency->id != $authUser->work_at &&
                !$authUser->hasPermission('manage_coupons') &&
                !$authUser->hasPermission('edit_coupon')
            ) {
                abort(403);
            }
            $confirm_agency_id = $authUser->work_at;
        }

        if($existing_coupon && $existing_coupon->id != $request->id){
            \LogActivity::addToLog("Coupon updation failed. Error: The selected space has been already created in this agency");
            return response([
                'errors' => "The selected space has been already created in this agency.",
            ], 422);
        }

        if(
            $authUser->hasPermission('manage_coupons') ||
            $authUser->hasPermission('edit_coupon')
        ) {
            $confirm_agency_id = $agency->id;
        }

        $coupon = Coupon::findOrFail($request->id);
        if($request->has('quantity') && isset($request->quantity)) {
            $coupon->quantity = $request->quantity;
        }
        if($request->has('price_hour') && isset($request->price_hour)) {
            $coupon->price_hour = $request->price_hour;
        }
        if($request->has('price_midday') && isset($request->price_midday)) {
            $coupon->price_midday = $request->price_midday;
        }
        if($request->has('price_day') && isset($request->price_day)) {
            $coupon->price_day = $request->price_day;
        }
        if($request->has('price_week') && isset($request->price_week)) {
            $coupon->price_week = $request->price_week;
        }
        if($request->has('price_month') && isset($request->price_month)) {
            $coupon->price_month = $request->price_month;
        }
        $coupon->space_id = $space->id;
        $coupon->created_by = $authUser->id;
        $coupon->agency_id = $confirm_agency_id;
        $coupon->update();

        $response = [
            'message' => "The coupon $coupon->id successfully updated.",
        ];

        \LogActivity::addToLog("The coupon $coupon->id has been update");

        return response($response, 201);
    }

    public function destroy(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_coupons') &&
            !$authUser->hasPermission('delete_coupon')
        ) {
            abort(403);
        }

        $coupon = Coupon::findOrFail($request->id);
        if (! Hash::check($request->password, $authUser->password)) {
            $response = [
                'password' => 'Wrong password.'
            ];
            \LogActivity::addToLog("Fail to delete the coupon $coupon->id . error: Wrong password");
            return response($response, 422);
        }

        $has_users = DB::table('coupon_users')->where('coupon_id', $request->id)->exists();
        if($has_users) {
            $response = [
                'error' => "The coupon $coupon->id has been sending to clients. You can not delete it",
            ];
            \LogActivity::addToLog("Fail to delete coupon $coupon->id . error: He has clients");
            return response($response, 422);
        } else {
            $coupon->delete();
            $response = [
                'message' => "The coupon $coupon->id  successfully deleted",
            ];

            \LogActivity::addToLog("The coupon $coupon->id deleted");
            return response($response, 201);
        }
    }
}
