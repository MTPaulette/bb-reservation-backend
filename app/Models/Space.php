<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Space extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description_en',
        'description_fr',
        'nb_place'
    ];

    public function ressources(): HasMany {
        return $this->hasMany(Ressource::class);
    }

    public function images(): HasMany {
        return $this->hasMany(Image::class);
    }

    public function characteristics(): BelongsToMany {
        return $this->belongsToMany(Characteristic::class, 'characteristicSpaces')
                    ->using(CharacteristicSpace::class)
                    ->withPivot('id');
                    // ->withPivot('id', 'from' , 'to', 'created_at');
    }
}
