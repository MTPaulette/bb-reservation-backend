<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpaceImageController extends Controller
{
    public function store(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_spaces') &&
            !$request->user()->hasPermission('edit_space')
        ) { 
            abort(403);
        }

        $space = Space::findOrFail($request->space_id);
        $request->validate([
            'images.*' => 'mimes:jpg,png,jpeg,webp|max:5000'
        ], [
            'images.*.mimes' => 'the file should be in one of the formats: jpg, png, jpeg, webp'
        ]);

        foreach ($request->file('images') as $file){
            // $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = $file->store("images/space/{$space->id}", 'public');
            $space->images()->save(new Image([
                'src' => $path
            ]));
        }
        \LogActivity::addToLog("The space $space->name images successfully uploaded.");
        $response = [
            'message' => "The space $space->name images successfully uploaded."
        ];
        return response($response, 201);
    }
    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(
            !$request->user()->hasPermission('manage_spaces') &&
            !$request->user()->hasPermission('edit_space')
        ) { 
            abort(403);
        }

        $image = Image::findOrFail($request->image_id);
        Storage::disk('public')->delete($image->src);
        $image->delete();

        \LogActivity::addToLog("The image sucessfully deleted.");
        $response = [
            'message' => "The image sucessfully deleted."
        ];
        return response($response, 201);
    }


    public function destroyy(Request $request)
    {
        $user = $request->user();
        Storage::disk('public')->delete($user->image);
        $user->image = null;
        $user->update();

        \LogActivity::addToLog("The user $user->lastname has deleted his profile pic.");
        $response = [
            'message' => "The user $user->lastname has deleted his profile pic."
        ];
        return response($response, 201);
    }
}
