<?php

namespace App\Http\Controllers;

use App\Models\Openingday;
use Illuminate\Http\Request;

class OpeningdayController extends Controller
{

    public function index(Request $request)
    {
        $days = Openingday::get(['id', 'name_fr', 'name_en']);
        return response()->json($days, 201);
    }
}