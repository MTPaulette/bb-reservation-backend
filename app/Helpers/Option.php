<?php

namespace App\Helpers;

use App\Models\Option as OptionModel;


class Option
{
    public static function getValue($name)
    {
    	return OptionModel::where('name', $name)->first()->value;
    }

}