<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->user()->hasPermission('manage_permissions')) {
            abort(403);
        }
        $options = Permission::orderBy('name')->get(['id', 'name', 'description']);
        return response()->json($options, 201);
    }
}
