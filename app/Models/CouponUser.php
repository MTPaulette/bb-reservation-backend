<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CouponUser extends Pivot
{
    use HasFactory;

    public function reservations(): BelongsToMany {
        return $this->belongsToMany(Reservation::class);
    }
}
