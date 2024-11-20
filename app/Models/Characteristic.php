<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Characteristic extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_en',
        'name_fr',
    ];

    public function spaces(): BelongsToMany {
        return $this->belongsToMany(Space::class, 'characteristicSpaces')
                    ->using(CharacteristicSpace::class)
                    ->withPivot('id');
                    // ->withPivot('id', 'from' , 'to', 'created_at');
    }
}
