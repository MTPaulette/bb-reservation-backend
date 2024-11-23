<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Ressource extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity',
        'price_hour',
        'price_midday',
        'price_day',
        'price_week',
        'price_month',
    ];

    public function agency(): BelongsTo {
        return $this->belongsTo(Agency::class);
    }

    public function space(): BelongsTo {
        return $this->belongsTo(Space::class);
    }

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

    public function scopeWithAgencySpaceUser(Builder $query): Builder {
        return $query->join('agencies', 'ressources.agency_id', 'agencies.id')
                    ->join('spaces', 'ressources.space_id', '=', 'spaces.id')
                    ->join('users', 'ressources.created_by', '=', 'users.id')
                    ->select(
                        'ressources.*',
                        'agencies.name as agency',
                        'spaces.name as space',
                        'spaces.nb_place as nb_place',
                        'users.firstname as parent_firstname',
                        'users.lastname as parent_lastname'
                    )
                    ->orderBy('spaces.name');
    }
}
