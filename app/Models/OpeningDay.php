<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class OpeningDay extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function agencies(): BelongsToMany {
        return $this->belongsToMany(Agency::class, 'agencyOpeningDays')
                    ->using(AgencyOpeningDay::class)
                    ->withPivot('id', 'from' , 'to', 'created_at');
    }
}