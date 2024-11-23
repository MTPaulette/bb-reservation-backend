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
        $options = Permission::orderBy('created_at')->get(['id', 'name', 'description_en', 'description_fr']);
        return response()->json($options, 201);
    }
}
