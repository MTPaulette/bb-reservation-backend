<?php

namespace App\Helpers;

use App\Models\User as UserModel;


class User
{
    public static function getSuperadminAndAdmins($agency_id)
    {
        $users = UserModel::where('role_id', 3)->get();
    	return $users;
    }

}

/*
    public static function getSuperadminAndAdmins($agency_id)
    {
        $users = UserModel::where('role_id', 3)
                        ->orWhere(function ($query) use ($agency_id) {
                            $query->where('role_id', 1)
                                ->where('work_at', $agency_id);
                        })->get();
    	return $users;
    }
*/