<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->user()->hasPermission('manage_options')) {
            abort(403);
        }
        // $options = Option::orderBy('name')->get(['id', 'name', 'value']);
        $options = Option::orderBy('name')->get();
        return response()->json($options, 201);
    }

    public function store(Request $request)
    {
        if(!$request->user()->hasPermission('manage_options')) {
            abort(403);
        }
        // return sizeof($request->options);
        if(sizeof($request->options) != 0) {
            foreach($request->options as $opt) {
                if(Option::where('id', $opt['id'])->exists()) {
                    $option = Option::find($opt['id']);
                    $option->value = $opt['value'];
                    $option->save();
                }
            }
        }

        $response = [
            'message' => "Options updated",
        ];

        \LogActivity::addToLog("Options updated");
        return response($response, 201);
    }

    public function save_holidays(Request $request)
    {
        if(!$request->user()->hasPermission('manage_options')) {
            abort(403);
        }

        $holidays = $request->holidays;
        Option::updateOrCreate(
            ['name' => 'holidays'],
            ['value' => implode(",", $holidays)]
        );

        $response = [
            'message' => "Holidays option updated",
        ];

        \LogActivity::addToLog("Holidays option updated");
        return response($response, 201);
    }
}