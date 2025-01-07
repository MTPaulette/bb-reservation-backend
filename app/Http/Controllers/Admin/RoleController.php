<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->user()->hasPermission('manage_permissions')) {
            abort(403);
        }
        $roles = Role::with('permissions')
            ->get()->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->map(function ($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                            'description_en' => $permission->description_en,
                            'description_fr' => $permission->description_fr,
                        ];
                    })->toArray(),
                ];
            });
        return response()->json($roles, 201);
    }

    public function show(Request $request)
    {
        if(!$request->user()->hasPermission('manage_permissions')) {
            abort(403);
        }
        $role = Role::where('id', $request->id)->with('permissions')
            ->get()->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->map(function ($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                            'description_en' => $permission->description_en,
                            'description_fr' => $permission->description_fr,
                        ];
                    })->toArray(),
                ];
            });
        if(sizeof($role) == 0){
            abort(404);
        }
        return response()->json($role, 201);
    }

    public function update(Request $request)
    {
        if(!$request->user()->hasPermission('manage_permissions')) {
            abort(403);
        }
        $role = Role::findOrFail($request->id);
        $existingPermissionIds = Permission::whereIn('id', $request->permissions)->pluck('id');
        $role->permissions()->sync($existingPermissionIds);
        $response = [
            'message' => "The role $role->name's permissions updated",
        ];

        \LogActivity::addToLog("The role $role->name's permissions updated");
        // $permissions = $request->user()->role->permissions->pluck('name')->toArray();
        $permissions = $request->user()->role->permissions->pluck('name')->toArray();

        $response = [
            'permissions' => $permissions,
        ];
        return response($response, 201);
    }
}


/*
    $role = Role::findOrFail($request->id);
    $t = "";
    for ($i=0; $i < 88; $i++) { 
        $t = $t.', '.$i;
    }
    return $t;
*/