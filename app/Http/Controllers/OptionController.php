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

    public function store(Request $request)
    {
        if(!$request->user()->hasPermission('manage_option')) {
            abort(403);
        }
        // return sizeof($request->options);
        if(sizeof($request->options) != 0) {
            foreach($request->options as $option) {
                Option::updateOrCreate(
                    ['name' => $option['name']],
                    ['value' => $option['value']]
                );
            }
        }

        $response = [
            'message' => "Options updated",
        ];

        \LogActivity::addToLog("Options updated");
        return response($response, 201);
    }
}
