<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AgencyOpeningDay extends Pivot
{
    use HasFactory;
    protected $fillable = [
        'from',
        'to',
    ];
}
