<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Agency;
use App\Models\Reservation;
use App\Models\Ressource;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RessourceController extends Controller
{
    public function ressourceAllInformations()
    {
        return
        Ressource::with([
            'createdBy' => function($query) {
                $query->select('id', 'lastname', 'firstname');
            },
            'agency' => function($query) {
                $query->select('id', 'name');
            },
            // 'reservations',
            'space' => [
                'images',
                'characteristics' => function($query) {
                    $query->select('name_en', 'name_fr');
                },
            ]
        ]);
    }

    public function index(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('manage_ressources') ||
            $authUser->hasPermission('show_all_ressource')
        ) {
            $ressources = Ressource::withAgencySpaceUser()->get()->toArray();
            return response()->json($ressources, 201);
        }
        if($authUser->hasPermission('show_all_ressource_of_agency')) {
            $ressources = Ressource::withAgencySpaceUser()->where('ressources.agency_id', $authUser->work_at)->get(); //->toArray();
            return response()->json($ressources, 201);
        }

        abort(403);
    }

    public function show(Request $request)
    {
        $authUser = $request->user();
        if(
            $authUser->hasPermission('manage_ressources') ||
            $authUser->hasPermission('view_ressource') ||
            $authUser->hasPermission('view_ressource_of_agency')
        ) {
            $ressource = Ressource::findOrFail($request->id);

            if(
                !$authUser->hasPermission('manage_ressources') &&
                !$authUser->hasPermission('view_ressource') &&
                $authUser->hasPermission('view_ressource_of_agency')
            ) {
                if($authUser->work_at != $ressource->agency_id) {
                    abort(403);
                }
            }

            $ressource = $this->ressourceAllInformations()->find($request->id);

            $reservations_results =
                Reservation::where('reservations.ressource_id', '=', $request->id)
                ->with([
                    'client' => function($query) {
                        $query->select('id', 'lastname', 'firstname');
                    },
                    'createdBy' => function($query) {
                        $query->select('id', 'lastname', 'firstname');
                    },
                    'ressource' => [
                        'space' => function($query) {
                            $query->select('id', 'name');
                        },
                        'agency' => function($query) {
                            $query->select('id', 'name');
                        },
                    ]
                ])
                ->orderByDesc('reservations.created_at')
                ->get();

            $reservations = [];
            foreach ($reservations_results as $reservation) {
                array_push($reservations, $reservation);
            };

            $response = [
                'ressource' => $ressource,
                'reservations' => $reservations,
            ];
            return response()->json($response, 201);
        }

        abort(403);
    }

    public function store(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_ressources') &&
            !$authUser->hasPermission('create_ressource') &&
            !$authUser->hasPermission('create_ressource_of_agency')
        ) {
            abort(403);
        }

        $validator = Validator::make($request->all(),[
            'quantity' => 'required|integer|min:1',
            'price_hour' => 'required|integer|min:1',
            'price_midday' => 'required|integer|min:1',
            'price_day' => 'required|integer|min:1',
            'price_week' => 'required|integer|min:1',
            'price_month' => 'required|integer|min:1',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Ressource creation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $agency = Agency::findOrFail($request->agency_id);
        $space = Space::findOrFail($request->space_id);

        if(
            Ressource::where('agency_id', $agency->id)
                    ->where('space_id', $space->id
                    )->exists()
        ){
            \LogActivity::addToLog("Ressource creation failed. Error: The selected space has been already created in this agency");
            return response([
                'errors' => "The selected space has been already created in this agency",
            ], 422);
        }

        if($authUser->hasPermission('create_ressource_of_agency')) {
            if(
                $agency->id != $authUser->work_at &&
                !$authUser->hasPermission('manage_ressources') &&
                !$authUser->hasPermission('create_ressource')
            ) {
                abort(403);
            }
            $confirm_agency_id = $authUser->work_at;
        }

        if(
            $authUser->hasPermission('manage_ressources') ||
            $authUser->hasPermission('create_ressource')
        ) {
            $confirm_agency_id = $agency->id;
        }

        $ressource = Ressource::create($validator->validated());
        $ressource->space_id = $space->id;
        $ressource->created_by = $authUser->id;
        $ressource->agency_id = $confirm_agency_id;
        $ressource->save();

        $response = [
            'message' => "The ressource $ressource->id successfully created",
        ];

        \LogActivity::addToLog("New ressource created. ressource id: $ressource->id");

        return response($response, 201);
    }

    public function update(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_ressources') &&
            !$authUser->hasPermission('edit_ressource') &&
            !$authUser->hasPermission('edit_ressource_of_agency')
        ) {
            abort(403);
        }
        $validator = Validator::make($request->all(),[
            'quantity' => 'required|integer|min:1',
            'price_hour' => 'required|integer|min:1',
            'price_midday' => 'required|integer|min:1',
            'price_day' => 'required|integer|min:1',
            'price_week' => 'required|integer|min:1',
            'price_month' => 'required|integer|min:1',
        ]);

        if($validator->fails()){
            \LogActivity::addToLog("Ressource updation failed. ".$validator->errors());
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }

        $agency = Agency::findOrFail($request->agency_id);
        $space = Space::findOrFail($request->space_id);
        $ressource = Ressource::findOrFail($request->id);

        $existing_ressource = Ressource::where('agency_id', $agency->id)
        ->where('space_id', $space->id)->first();

        if($authUser->hasPermission('edit_ressource_of_agency')) {
            if(
                $agency->id != $authUser->work_at &&
                !$authUser->hasPermission('manage_ressources') &&
                !$authUser->hasPermission('edit_ressource')
            ) {
                abort(403);
            }
            $confirm_agency_id = $authUser->work_at;
        }

        if($existing_ressource && $existing_ressource->id != $request->id){
            \LogActivity::addToLog("Ressource updation failed. Error: The selected space has been already created in this agency");
            return response([
                'errors' => "The selected space has been already created in this agency.",
            ], 422);
        }

        if(
            $authUser->hasPermission('manage_ressources') ||
            $authUser->hasPermission('edit_ressource')
        ) {
            $confirm_agency_id = $agency->id;
        }

        $ressource = Ressource::findOrFail($request->id);
        if($request->has('quantity') && isset($request->quantity)) {
            $ressource->quantity = $request->quantity;
        }
        if($request->has('price_hour') && isset($request->price_hour)) {
            $ressource->price_hour = $request->price_hour;
        }
        if($request->has('price_midday') && isset($request->price_midday)) {
            $ressource->price_midday = $request->price_midday;
        }
        if($request->has('price_day') && isset($request->price_day)) {
            $ressource->price_day = $request->price_day;
        }
        if($request->has('price_week') && isset($request->price_week)) {
            $ressource->price_week = $request->price_week;
        }
        if($request->has('price_month') && isset($request->price_month)) {
            $ressource->price_month = $request->price_month;
        }
        $ressource->space_id = $space->id;
        $ressource->created_by = $authUser->id;
        $ressource->agency_id = $confirm_agency_id;
        $ressource->update();

        $response = [
            'message' => "The ressource $ressource->id successfully updated.",
        ];

        \LogActivity::addToLog("The ressource $ressource->id has been update");

        return response($response, 201);
    }

    public function destroy(Request $request)
    {
        $authUser = $request->user();
        if(
            !$authUser->hasPermission('manage_ressources') &&
            !$authUser->hasPermission('delete_ressource') &&
            !$authUser->hasPermission('delete_ressource_of_agency')
        ) {
            abort(403);
        }

        $ressource = Ressource::findOrFail($request->id);
        if (! Hash::check($request->password, $authUser->password)) {
            $response = [
                'password' => 'Wrong password.'
            ];
            \LogActivity::addToLog("Fail to delete the ressource $ressource->id . error: Wrong password");
            return response($response, 422);
        }

        if($authUser->hasPermission('delete_ressource_of_agency')) {
            if(
                $ressource->agency_id != $authUser->work_at &&
                !$authUser->hasPermission('manage_ressources') &&
                !$authUser->hasPermission('delete_ressource')
            ) {
                abort(403);
            }
        }

        $has_reservation = Reservation::where('ressource_id', $request->id)->exists();
        if($has_reservation) {
            $response = [
                'error' => "The ressource $ressource->id has reservation. You can not delete it",
            ];
            \LogActivity::addToLog("Fail to delete ressource $ressource->id . error: He has users or ressource or opening days");
            return response($response, 422);
        } else {
            $ressource->delete();
            $response = [
                'message' => "The ressource $ressource->id  successfully deleted",
            ];

            \LogActivity::addToLog("The ressource $ressource->id deleted");
            return response($response, 201);
        }
    }
}
