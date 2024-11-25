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

    public function ressources(): BelongsToMany {
        return $this->belongsToMany(Ressource::class, 'characteristicRessources')
                    ->using(CharacteristicRessource::class)
                    ->withPivot('id');
                    // ->withPivot('id', 'from' , 'to', 'created_at');
    }
}
