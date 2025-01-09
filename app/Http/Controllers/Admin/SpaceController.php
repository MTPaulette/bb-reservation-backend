<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Space;
use App\Models\Characteristic;
use App\Models\Ressource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class SpaceController extends Controller
{
    public function spaceWithCharacteristic()
    {
        return
        Space::with('characteristics')
            ->with('images')
            ->get()->map(function ($space) {
                return [
                    'id' => $space->id,
                    'name' => $space->name,
                    'description_en' => $space->description_en,
                    'description_fr' => $space->description_fr,
                    'nb_place' => $space->nb_place,
                    'created_at' => $space->created_at,
                    'characteristics' => $space->characteristics->map(function ($characteristic) {
                        // return $characteristic;
                        return [
                            'id' => $characteristic->id,
                            'name_en' => $characteristic->name_en,
                            'name_fr' => $characteristic->name_fr,
                        ];
                    })->toArray(),
                    'images' => $space->images->map(function ($image) {
                        // return $characteristic;
                        return [
                            'id' => $image->id,
                            'src' => $image->src,
                        ];
                    })->toArray()
                ];
        });
    }

    public function index(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_spaces') &&
            !$request->user()->hasPermission('show_all_space')
        ) {
            abort(403);
        }
        $spaces = $this->spaceWithCharacteristic();
        return response()->json($spaces, 201);
    }

    public function show(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_spaces') &&
            !$request->user()->hasPermission('view_space')
        ) {
            abort(403);
        }
        // $space = $this->spaceWithCharacteristic()->where('id', $request->id)->toArray();
        $space = Space::findOrFail($request->id);
        $space = $this->spaceWithCharacteristic()->where('id', $request->id)->first();
        return response()->json($space, 201);
    }

    public function store(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_spaces') &&
            !$request->user()->hasPermission('create_space')
        ) {
            abort(403);
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|unique:spaces|max:250',
            'description_en' => 'required|string',
            'description_fr' => 'required|string',
            'nb_place' => 'required|integer|min:1',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Space creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $space = Space::create($validator->validated());
        // $space->created_by = $request->user()->id;
        // $space->save();
        $response = [
            'message' => "The space $space->name successfully created",
        ];

        \LogActivity::addToLog("New space created. space name: $space->name");

        return response($response, 201);
    }

    public function update(Request $request)
    {
        if(
            $request->user()->hasPermission('manage_spaces') ||
            $request->user()->hasPermission('edit_space')
        ) {
            $validator = Validator::make($request->all(),[
                'description_en' => 'required|string',
                'description_fr' => 'required|string',
                'nb_place' => 'required|integer|min:1',
            ]);

            if($validator->fails()){
                \LogActivity::addToLog("Space updation failed. ".$validator->errors());
                return response([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $space = Space::findOrFail($request->id);
            $request->validate([
                'name' => [
                    'required', 'string', 'max:250',
                    Rule::unique('spaces', 'name')->ignore($request->id),
                ],
            ]);
            if($request->has('name') && isset($request->name)) {
                $space->name = $request->name;
            }
            if($request->has('description_en') && isset($request->description_en)) {
                $space->description_en = $request->description_en;
            }
            if($request->has('description_fr') && isset($request->description_fr)) {
                $space->description_fr = $request->description_fr;
            }
            if($request->has('nb_place') && isset($request->nb_place)) {
                $space->nb_place = $request->nb_place;
            }
            if($request->has('characteristics') && isset($request->characteristics)) {
                $space->characteristics()->detach();
                $characteristics = $request->characteristics;
                foreach ($characteristics as $characteristic_id) {
                    $characteristic = Characteristic::findOrFail($characteristic_id);
                    $space->characteristics()->attach($characteristic);
                }
            }

            $space->update();
            $response = [
                'message' => "The $space->name successfully updated",
            ];

            \LogActivity::addToLog("The space $space->name has been updated.");
            // return response($space, 201);
            return response($response, 201);
        }
        abort(403);
    }

    public function destroy(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_spaces') &&
            !$authUser->hasPermission('delete_space')
        ) {
            abort(403);
        }

        $space = Space::findOrFail($request->id);
        if (! Hash::check($request->password, $authUser->password)) {
            $response = [
                'password' => 'Wrong password.'
            ];
            \LogActivity::addToLog("Fail to delete $space->name . error: Wrong password");
            return response($response, 422);
        }
    
        $has_ressource = Ressource::where('space_id', $request->id)->exists();
        $has_characteristic = DB::table('characteristicSpaces')->where('space_id', $request->id)->exists();
    
        if($has_ressource || $has_characteristic) {
            $response = [
                'error' => "The $space->name has characteristic or ressource. You can not delete it",
            ];
            \LogActivity::addToLog("Fail to delete space $space->name . error: He has users or ressource or opening days");
            return response($response, 422);
        } else {
            $space->delete();
            $response = [
                'message' => "The $space->name  successfully deleted",
            ];
    
            \LogActivity::addToLog("The space $space->name  deleted");
            return response($response, 201);
        }
    }
}
