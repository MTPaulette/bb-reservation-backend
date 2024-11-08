<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->user()->hasPermission('manage_agency')) {
            abort(403);
        }
        $agencies = Agency::orderBy('name')->get();
        return response()->json($agencies, 201);
    }

    public function show(Request $request)
    {
        if(!$request->user()->hasPermission('manage_agency')) {
            abort(403);
        }
        $agency = Agency::where('id', $request->id)->orderBy('name')->with('openingdays')->get();
        if(sizeof($agency) == 0){
            abort(404);
        }
        return response()->json($agency, 201);
    }

}
