<?php

namespace App\Http\Controllers;

use App\Models\Characteristic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CharacteristicController extends Controller
{

    public function index(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_spaces') &&
            !$request->user()->hasPermission('create_space') &&
            !$request->user()->hasPermission('edit_space') 
        ) {
            abort(403);
        }
        $characteristics = Characteristic::orderBy('name_en')->get(['id', 'name_en', 'name_fr']);
        return response()->json($characteristics, 201);
    }

    public function store(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_spaces') &&
            !$request->user()->hasPermission('create_space') &&
            !$request->user()->hasPermission('edit_space') 
        ) {
            abort(403);
        }
        $validator = Validator::make($request->all(),[
            'name_en' => 'required|string|max:250',
            'name_fr' => 'required|string|max:250'
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Characteristics creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 500);
        }

        $characteristic = Characteristic::create($validator->validated());
        $characteristic->save();
        $response = [
            'message' => "The characteristic $characteristic->name_en successfully created",
        ];

        \LogActivity::addToLog("New Characteristics created. Characteristic name: $characteristic->name");

        return response($response, 201);
    }

    public function update(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_spaces') &&
            !$request->user()->hasPermission('create_space') &&
            !$request->user()->hasPermission('edit_space') 
        ) {
            abort(403);
        }
        $validator = Validator::make($request->all(),[
            'name_en' => 'required|string|max:250',
            'name_fr' => 'required|string|max:250'
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Characteristics updation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 500);
        }

        $characteristic = Characteristic::findOrFail($request->id);
        if($request->has('name_en') && isset($request->name_en)) {
            $characteristic->name_en = $request->name_en;
        }
        if($request->has('name_fr') && isset($request->name_fr)) {
            $characteristic->name_fr = $request->name_fr;
        }
        $characteristic->update();
        \LogActivity::addToLog("The characteristic $characteristic->name_en has been updated.");
        return response($characteristic, 201);
    }

    public function destroy(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_spaces') &&
            !$authUser->hasPermission('create_space') &&
            !$authUser->hasPermission('edit_space') 
        ) {
            abort(403);
        }
        $characteristic = characteristic::findOrFail($request->id);
        if (! Hash::check($request->password, $authUser->password)) {
            $response = [
                'password' => 'Wrong password.'
            ];
            \LogActivity::addToLog("Fail to delete $characteristic->name_en . error: Wrong password");
            return response($response, 422);
        }
        $has_space = DB::table('characteristicSpaces')->where('characteristic_id', $request->id)->exists();
        if($has_space) {
            $response = [
                'error' => "The $characteristic->name_en has spaces. You can not delete it",
            ];
            \LogActivity::addToLog("Fail to delete characteristic $characteristic->name_en . error: He has space.");
            return response($response, 422);
        } else {
            $characteristic->delete();
            $response = [
                'message' => "The $characteristic->name_en  successfully deleted",
            ];
    
            \LogActivity::addToLog("The characteristic $characteristic->name_en  deleted");
            return response($response, 201);
        }
    }

}
