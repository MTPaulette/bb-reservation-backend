<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
 
        $user = $request->user();
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        $user->image = $imageName;
        $user->update();
        $response = [
            'message' => "The user $user->lastname has uploaded his profile pic.",
            'src' => $user->image,
        ];
        return response($response, 201);
    }
    
    public function getImage()
    {
        $imageName = 'image.jpg'; // Remplacez par le nom de l'image que vous avez enregistré
        $filePath = public_path('images/'.$imageName);

        if (file_exists($filePath)) {
            return response()->json($imageName);
        } else {
            return response()->json('Image not found');
        }
    }


    public function store(Request $request)
    {
        $user = $request->user();

        if($request->hasFile('image')) {
            $request->validate([
                'images.*' => 'mimes:jpg,png,jpeg,webp|max:5000'
            ], [
                'images.*.mimes' => 'the file should be in one of the formats: jpg, png, jpeg, webp'
            ]);

            if(isset($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $path = $request->file('image')->store('images/user', 'public');
            $user->image = $path;
            
            // foreach ($request->file('image') as $file){
            // return 'yes';
            //     $path = $file->store('images/user', 'public');
            //     $user->image = $path;
            // }
        }

        $user->update();

        \LogActivity::addToLog("The user $user->lastname has uploaded his profile pic.");
        $response = [
            'message' => "The user $user->lastname has uploaded his profile pic.",
            'src' => $user->image,
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
