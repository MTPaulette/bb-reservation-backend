<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function created_by(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }
}
