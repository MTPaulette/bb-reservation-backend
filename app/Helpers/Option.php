<?php

namespace App\Helpers;

use App\Models\Option as OptionModel;


class Option
{
    public static function getValue($name)
    {
        if(OptionModel::where('name', $name)->exists()) {
    	    return OptionModel::where('name', $name)->first()->value;
        };
        return null;
    }

}