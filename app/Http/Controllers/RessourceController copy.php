<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Characteristic;
use App\Models\Ressource;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RessourceController extends Controller
{
    public function index(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('manage_ressources') ||
            $authUser->hasPermission('show_all_ressource')
        ) {
            $ressources = Ressource::withAgencySpaceUser()->get()->toArray();
            return response()->json($ressources, 201);
        }
        if($authUser->hasPermission('show_all_ressource_of_agency')) {
            $ressources = Ressource::withAgencySpaceUser()->where('ressources.agency_id', $authUser->work_at)->get(); //->toArray();
            return response()->json($ressources, 201);
        }

        abort(403);
    }

    public function show(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_ressources') &&
            !$request->user()->hasPermission('show_all_ressource')
        ) {
            abort(403);
        }
        // $ressource = $this->ressourceWithCharacteristic()->where('id', $request->id)->toArray();
        $ressource = $this->ressourceWithCharacteristic()->where('id', $request->id)->first();
        if(sizeof($ressource) == 0){
            abort(404);
        }
        return response()->json($ressource, 201);
    }

    public function store(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_ressource') &&
            !$authUser->hasPermission('create_ressource') &&
            !$authUser->hasPermission('create_ressource_of_agency')
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
            \LogActivity::addToLog("Ressource creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $agency = Agency::findOrFail($request->agency_id);
        $space = Space::findOrFail($request->space_id);

        if(
            Ressource::where('agency_id', $agency->id)
                    ->where('space_id', $space->id
                    )->exists()
        ){
            \LogActivity::addToLog("Ressource creation failed. Error: The selected space has been already created in this agency");
            return response([
                'errors' => "The selected space has been already created in this agency",
            ], 422);
        }

        // $ressource = Ressource::create($validator->validated());
        $ressource->space_id = $space->id;
        $ressource->created_by = $request->user()->id;
        if($authUser->hasPermission('create_ressource_of_agency')) {
            if(
                $agency->id != $authUser->work_at &&
                !$authUser->hasPermission('manage_ressource') &&
                !$authUser->hasPermission('create_ressource')
            ) {
                abort(403);
            }
            $ressource->agency_id = $authUser->work_at;
        }
        if(
            $authUser->hasPermission('manage_ressource') ||
            $authUser->hasPermission('create_ressource')
        ) {
            $ressource->agency_id = $agency->id;
        }
            $ressource->save();

        $response = [
            'message' => "The ressource $ressource->name successfully created",
        ];

        \LogActivity::addToLog("New ressource created. ressource name: $ressource->name");

        return response($response, 201);
    }

    public function update(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_ressource') &&
            !$authUser->hasPermission('create_ressource') &&
            !$authUser->hasPermission('create_ressource_of_agency')
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
            \LogActivity::addToLog("Ressource updation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $agency = Agency::findOrFail($request->agency_id);
        $space = Space::findOrFail($request->space_id);

        return Ressource::where('agency_id', $agency->id)
        ->where('space_id', $space->id)->first()->id;

        if(
            Ressource::where('agency_id', $agency->id)
                    ->where('space_id', $space->id
                    )->exists()
        ){
            \LogActivity::addToLog("Ressource creation failed. Error: The selected space has been already created in this agency");
            return response([
                'errors' => "The selected space has been already created in this agency",
            ], 422);
        }

        $ressource = Ressource::create($validator->validated());
        $ressource->space_id = $space->id;
        $ressource->created_by = $request->user()->id;
        if($authUser->hasPermission('create_ressource_of_agency')) {
            if(
                $agency->id != $authUser->work_at &&
                !$authUser->hasPermission('manage_ressource') &&
                !$authUser->hasPermission('create_ressource')
            ) {
                abort(403);
            }
            $ressource->agency_id = $authUser->work_at;
        }
        if(
            $authUser->hasPermission('manage_ressource') ||
            $authUser->hasPermission('create_ressource')
        ) {
            $ressource->agency_id = $agency->id;
        }
            $ressource->save();

        $response = [
            'message' => "The ressource $ressource->name successfully created",
        ];

        \LogActivity::addToLog("New ressource created. ressource name: $ressource->name");

        return response($response, 201);
    }


    public function updatee(Request $request)
    {
        if(
            $request->user()->hasPermission('manage_ressources') ||
            $request->user()->hasPermission('edit_ressource')
        ) {
            $validator = Validator::make($request->all(),[
                // 'name' => 'required|string|unique:ressources|max:250',
                'description_en' => 'required|string',
                'description_fr' => 'required|string',
                'nb_place' => 'required|string',
            ]);

            if($validator->fails()){
                \LogActivity::addToLog("Ressource updation failed. ".$validator->errors());
                return response([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $ressource = Ressource::findOrFail($request->id);
            $request->validate([
                'name' => [
                    'required', 'string', 'max:250',
                    Rule::unique('ressources', 'name')->ignore($request->id),
                ],
            ]);
            if($request->has('name') && isset($request->name)) {
                $ressource->name = $request->name;
            }
            if($request->has('description_en') && isset($request->description_en)) {
                $ressource->description_en = $request->description_en;
            }
            if($request->has('description_fr') && isset($request->description_fr)) {
                $ressource->description_fr = $request->description_fr;
            }
            if($request->has('nb_place') && isset($request->nb_place)) {
                $ressource->nb_place = $request->nb_place;
            }
            if($request->has('characteristics') && isset($request->characteristics)) {
                $ressource->characteristics()->detach();
                $characteristics = $request->characteristics;
                foreach ($characteristics as $characteristic_id) {
                    $characteristic = Characteristic::findOrFail($characteristic_id);
                    $ressource->characteristics()->attach($characteristic);
                }
            }

            $ressource->update();
            $response = [
                'message' => "The $ressource->name successfully updated",
            ];

            \LogActivity::addToLog("The ressource $ressource->name has been updated.");
            // return response($ressource, 201);
            return response($response, 201);
        }
        abort(403);
    }

    public function destroy(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_ressources') &&
            !$authUser->hasPermission('delete_ressource')
        ) {
            abort(403);
        }

        $ressource = Ressource::findOrFail($request->id);
        if (! Hash::check($request->password, $authUser->password)) {
            $response = [
                'password' => 'Wrong password.'
            ];
            \LogActivity::addToLog("Fail to delete $ressource->name . error: Wrong password");
            return response($response, 422);
        }
    
        $has_ressource = Ressource::where('ressource_id', $request->id)->exists();
        $has_characteristic = DB::table('characteristicRessources')->where('ressource_id', $request->id)->exists();
    
        if($has_ressource || $has_characteristic) {
            $response = [
                'error' => "The $ressource->name has characteristic or ressource. You can not delete it",
            ];
            \LogActivity::addToLog("Fail to delete ressource $ressource->name . error: He has users or ressource or opening days");
            return response($response, 422);
        } else {
            $ressource->delete();
            $response = [
                'message' => "The $ressource->name  successfully deleted",
            ];
    
            \LogActivity::addToLog("The ressource $ressource->name  deleted");
            return response($response, 201);
        }
    }
}
