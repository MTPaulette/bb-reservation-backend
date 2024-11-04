<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Agency extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'email',
        'phonenumber',
        'reason_for_suspension',
    ];

    public function openingDays(): BelongsToMany {
        return $this->belongsToMany(OpeningDay::class, 'agencyOpeningDays')
                    ->using(AgencyOpeningDay::class)
                    ->withPivot('id', 'from' , 'to', 'created_at');
    }

    public function created_by(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function administrators(): HasMany {
        return $this->hasMany(User::class);
    }

    public function ressources(): HasMany {
        return $this->hasMany(Ressource::class);
    }
}
