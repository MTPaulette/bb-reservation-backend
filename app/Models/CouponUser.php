<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponUser extends Pivot
{
    use HasFactory;
    protected $fillable = [
        'nb_usage',
        'used_on',
    ];

    public function payment(): BelongsTo {
        return $this->belongsTo(Payment::class);
    }
}
