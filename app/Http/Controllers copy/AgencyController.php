<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Openingday;
use App\Models\Ressource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AgencyController extends Controller
{
    public function agencyAllInformations()
    {
        return
        Agency::with([
            'createdBy',
            'suspendedBy',
            'administrators',
            'openingdays',
            'ressources.space',
            'ressources.reservations'
        ]);
    }

    public function agencyWithOpeningDay()
    {
        return
        Agency::with('openingdays')
            ->get()->map(function ($agency) {
                return [
                    'id' => $agency->id,
                    'name' => $agency->name,
                    'address' => $agency->address,
                    'email' => $agency->email,
                    'phonenumber' => $agency->phonenumber,
                    'status' => $agency->status,
                    'reason_for_suspension_en' => $agency->reason_for_suspension_en,
                    'reason_for_suspension_fr' => $agency->reason_for_suspension_fr,
                    'created_at' => $agency->created_at,
                    'created_by' => $agency->created_by,
                    'parent_firstname' => $agency->created_by ? User::findOrFail($agency->created_by)->firstname: null,
                    'parent_lastname' => $agency->created_by ? User::findOrFail($agency->created_by)->lastname: null,
                    'openingdays' => $agency->openingdays->map(function ($openingday) {
                        return [
                            'id' => $openingday->id,
                            'name_en' => $openingday->name_en,
                            'name_fr' => $openingday->name_fr,
                            'from' => $openingday->pivot->from,
                            'to' => $openingday->pivot->to,
                        ];
                    })->toArray(),
                ];
        });
    }

    public function index(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_agency') &&
            !$request->user()->hasPermission('manage_all_agencies')
        ) {
            abort(403);
        }
        $agencies = $this->agencyWithOpeningDay();
        return response()->json($agencies, 201);
    }

    public function show(Request $request)
    {
        $authUser = $request->user();
        $agency = Agency::findOrFail($request->id);
        if($authUser->hasPermission('manage_all_agencies')) {
            // $agency = $this->agencyWithOpeningDay()->where('id', $request->id)->first();
            $agency = $this->agencyAllInformations()->find($request->id);
            return response()->json($agency, 201);
        }
        if($authUser->hasPermission('manage_agency')) {
            if($authUser->work_at == $agency->id) {
                // $agency = $this->agencyWithOpeningDay()->where('id', $request->id)->first();
                $agency = $this->agencyAllInformations()->find($request->id);
                return response()->json($agency, 201);
            }
        }
        abort(403);
    }


    public function showw(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_agency') &&
            !$request->user()->hasPermission('manage_all_agencies')
        ) {
            abort(403);
        }
        // $agency = $this->agencyWithOpeningDay()->where('id', $request->id)->toArray();
        $agency = $this->agencyWithOpeningDay()->where('id', $request->id)->first();
        if(sizeof($agency) == 0){
            abort(404);
        }
        return response()->json($agency, 201);
    }
    public function store(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_agency') &&
            !$request->user()->hasPermission('manage_all_agencies')
        ) {
            abort(403);
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|unique:agencies|max:250',
            'email' => 'required|string|email|max:250',
            'phonenumber' => 'required|string|min:9|max:250',
            'address' => 'required|string|max:250'
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Agency creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $agency = Agency::create($validator->validated());
        $agency->created_by = $request->user()->id;
        $agency->save();
        $response = [
            'message' => "The agency $agency->name successfully created",
        ];

        \LogActivity::addToLog("New agency created. agency name: $agency->name");

        return response($response, 201);
    }

    public function update(Request $request)
    {
        if(
            $request->user()->hasPermission('manage_agency') ||
            $request->user()->hasPermission('manage_agency')
        ) {
            $validator = Validator::make($request->all(),[
                // 'name' => 'required|string|unique:agencies',
                // 'name' => 'required|string|max:250',
                'email' => 'string|email|max:250',
                'phonenumber' => 'string|min:9|max:250',
                'address' => 'string|max:250'
            ]);

            if($validator->fails()){
                \LogActivity::addToLog("Agency updation failed. ".$validator->errors());
                return response([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $agency = Agency::findOrFail($request->id);
            $request->validate([
                'name' => [
                    'required', 'string', 'max:250',
                    Rule::unique('agencies', 'name')->ignore($request->id),
                ],
            ]);
            if($request->has('name') && isset($request->name)) {
                $agency->name = $request->name;
            }
            if($request->has('email') && isset($request->email)) {
                $agency->email = $request->email;
            }
            if($request->has('phonenumber') && isset($request->phonenumber)) {
                $agency->phonenumber = $request->phonenumber;
            }
            if($request->has('address') && isset($request->address)) {
                $agency->address = $request->address;
            }
            if($request->has('horaires') && isset($request->horaires)) {
                $agency->openingdays()->detach();
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                
                $horaires = $request->horaires;
                foreach ($days as $day) {
                    if(isset($horaires[$day])){
                        $openingday = Openingday::where('name_en', $day)->first();
                        $agency->openingdays()->attach($openingday, [
                            'from' => isset($horaires[$day]['from']) ? $horaires[$day]['from']: '08:00',
                            'to' => isset($horaires[$day]['to']) ? $horaires[$day]['to']: '18:00',
                        ]);
                    }
                }
            }

            $agency->update();
            \LogActivity::addToLog("The agency $agency->name has been updated.");
            return response($agency, 201);
        }
        abort(403);
    }

    
    public function destroy(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_agency') &&
            !$authUser->hasPermission('manage_all_agencies')
        ) {
            abort(403);
        }

        $agency = Agency::findOrFail($request->id);
        if (! Hash::check($request->password, $authUser->password)) {
            $response = [
                'password' => 'Wrong password.'
            ];
            \LogActivity::addToLog("Fail to delete $agency->name . error: Wrong password");
            return response($response, 422);
        }
    
        $has_ressource = Ressource::where('agency_id', $request->id)->exists();
        $has_openingday = DB::table('agencyOpeningdays')->where('agency_id', $request->id)->exists();
        $has_user = User::where('work_at', $request->id)->exists();
    
        if($has_ressource || $has_user || $has_openingday) {
            $response = [
                'error' => "The $agency->name has users or ressource or opening days. You can not delete it",
            ];
            \LogActivity::addToLog("Fail to delete agency $agency->name . error: He has maked users or ressource or opening days.");
            return response($response, 422);
        } else {
            $agency->delete();
            $response = [
                'message' => "The $agency->name  successfully deleted",
            ];
    
            \LogActivity::addToLog("The agency $agency->name  deleted");
            return response($response, 201);
        }
    }

    public function suspend(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('manage_agency') ||
            $authUser->hasPermission('manage_all_agencies')
        ) {
            $agency = Agency::findOrFail($request->id);
            if (!Hash::check($request->password, $authUser->password)) {
                $response = [
                    'password' => "Wrong password. $request->password"
                ];
                \LogActivity::addToLog("Agency $agency->name suspension failed. error: Wrong password");
                return response($response, 422);
            }

            if($request->cancel_suspension){
                $agency->status = 'active';
                $response = [
                    'message' => "The agency $agency->name 's suspension is stopped",
                ];
            } else {
                /*
                $validator = Validator::make($request->all(),[
                    'reason_for_suspension_en' => 'required|string|max:250',
                    'reason_for_suspension_fr' => 'required|string|max:250',
                ]);
    
                if($validator->fails()){
                    \LogActivity::addToLog("Agency $agency->name suspension failed. ".$validator->errors());
                    return response([
                        'errors' => $validator->errors(),
                    ], 422);
                } */

                $agency->status = 'suspended';
                $agency->reason_for_suspension_en = $request->reason_for_suspension_en;
                $agency->reason_for_suspension_fr = $request->reason_for_suspension_fr;
                $response = [
                    'message' => "The agency $agency->name successfully suspended",
                ];
            }
            $agency->save();
            \LogActivity::addToLog("The agency $agency->name status updated");
            return response($response, 201);
        }
        abort(403);
    }
}




// $u10->services()->attach($s1, ['by_cash' => true, 'credit' => '1']);
