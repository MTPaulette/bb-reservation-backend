<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Openingday extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_en',
        'name_fr'
    ];

    public function agencies(): BelongsToMany {
        return $this->belongsToMany(Agency::class, 'agencyOpeningdays')
                    ->using(AgencyOpeningday::class)
                    ->withPivot('id', 'from' , 'to', 'created_at');
    }
}
