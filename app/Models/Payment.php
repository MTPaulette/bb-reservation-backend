<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'note',
    ];

    public function payment_mode(): BelongsTo {
        return $this->belongsTo(PaymentMode::class);
    }

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }

    public function coupons(): HasMany {
        return $this->hasMany(CouponUser::class);
    }
}
