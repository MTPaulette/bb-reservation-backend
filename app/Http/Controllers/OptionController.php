<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->user()->hasPermission('manage_option')) {
            abort(403);
        }
        $options = Option::orderBy('name')->get(['id', 'name', 'value']);
        return response()->json($options, 201);
    }
}
