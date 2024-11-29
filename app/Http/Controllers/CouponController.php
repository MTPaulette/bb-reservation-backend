<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\User;
use App\Notifications\NewCouponReceived;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
        ])->withCount('users');
    }

    public function couponAllInformations()
    {
        return
        Coupon::with([
            'createdBy' => function($query) {
                $query->select('id', 'lastname', 'firstname');
            },
            'users' => function($query) {
                $query->select('user_id', 'lastname', 'firstname');
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
            $coupons = $this->couponWithCreator()->get();
            return response()->json($coupons, 201);
        }

        abort(403);
    }

    public function show(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('manage_coupons') ||
            $authUser->hasPermission('edit_coupon') ||
            $authUser->hasPermission('view_coupon')
        ) {
            $coupon = $this->couponAllInformations()->findOrFail($request->id);
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
        if(
            $request->user()->hasPermission('manage_coupons') ||
            $request->user()->hasPermission('edit_coupon')
        ) {
            $validator = Validator::make($request->all(),[
                'total_usage' => 'required|integer|min:1',
                'percent' => 'nullable|integer|min:1',
                'amount' => 'nullable|integer|min:1',
                'expired_on' => 'required|date|after:'.Carbon::today()->format('Y-m-d H:m:s'), //after, before
                'note_en' => 'string|nullable',
                'note_fr' => 'string|nullable',
            ]);

            if($validator->fails()){
                \LogActivity::addToLog("Coupon updation failed. ".$validator->errors());
                return response([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $coupon = Coupon::findOrFail($request->id);
            $request->validate([
                'name' => [
                    'required', 'string', 'max:250',
                    Rule::unique('coupons', 'name')->ignore($request->id),
                ],
            ]);
            if($request->has('name') && isset($request->name)) {
                $coupon->name = $request->name;
            }
            if($request->has('total_usage') && isset($request->total_usage)) {
                $coupon->total_usage = $request->total_usage;
            }
            if($request->has('percent') && isset($request->percent)) {
                $coupon->percent = $request->percent;
            }
            if($request->has('amount') && isset($request->amount)) {
                $coupon->amount = $request->amount;
            }
            if($request->has('expired_on') && isset($request->expired_on)) {
                $coupon->expired_on = $request->expired_on;
            }
            if($request->has('note_en') && isset($request->note_en)) {
                $coupon->note_en = $request->note_en;
            }
            if($request->has('note_fr') && isset($request->note_fr)) {
                $coupon->note_fr = $request->note_fr;
            }
            if($request->has('clients') && isset($request->clients)) {
                $coupon->users()->detach();
                $clients = $request->clients;
                foreach ($clients as $client_id) {
                    $client = User::findOrFail($client_id);
                    $coupon->users()->attach($client);
                }
            }

            $coupon->update();
            $response = [
                'message' => "The $coupon->name successfully updated",
            ];
            foreach($coupon->users as $user) {
                $user->notify(new NewCouponReceived($coupon));
            }

            \LogActivity::addToLog("The coupon $coupon->name has been updated.");
            // return response($coupon, 201);
            return response($response, 201);
        }
        abort(403);
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
