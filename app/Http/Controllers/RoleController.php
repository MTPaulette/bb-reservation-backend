<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->user()->hasPermission('manage_permissions')) {
            abort(403);
        }
        $roles = Role::orderBy('name')->get(['id', 'name']);
        return response()->json($roles, 201);
    }

    public function show(Request $request)
    {
        if(!$request->user()->hasPermission('manage_permissions')) {
            abort(403);
        }
        $role = Role::where('id', $request->id)->orderBy('name')->with('permissions')->get();
        if(sizeof($role) == 0){
            abort(404);
        }
        return response()->json($role, 201);
    }

    /*
    public function permissions(Request $request)
    {
        if(!$request->user()->hasPermission('manage_permissions')) {
            abort(403);
        }
        $role = Role::findOrFail($request->id);
        $query = DB::table('permission_role')
                ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                ->join('roles', 'permission_role.role_id', '=', 'roles.id')
                ->select('permission_role.*', 'roles.name as role', 'permissions.name as permissions', 'permissions.description as description')
                ->orderBy('description');

        $permissions = $query->where('role_id', $role->id)->get();
        return response()->json($permissions, 201);
    } */

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