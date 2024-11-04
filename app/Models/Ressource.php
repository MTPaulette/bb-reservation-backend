<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ressource extends Model
{
    use HasFactory;
    protected $fillable = [
        'price',
        'credit',
        'debit',
    ];

    public function agency(): BelongsTo {
        return $this->belongsTo(Agency::class);
    }

    public function space(): BelongsTo {
        return $this->belongsTo(Space::class);
    }

    public function created_by(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function validity(): BelongsTo {
        return $this->belongsTo(Validity::class);
    }

    public function characteristics(): BelongsToMany {
        return $this->belongsToMany(Characteristic::class, 'characteristicRessources')
                    ->using(CharacteristicRessource::class)
                    ->withPivot('id');
                    // ->withPivot('id', 'from' , 'to', 'created_at');
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }
}
