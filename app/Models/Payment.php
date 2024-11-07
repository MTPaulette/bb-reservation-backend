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
        'payment_method',
        'payment_status',
        'transaction_id',
    ];

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }

    public function coupons(): HasMany {
        return $this->hasMany(CouponUser::class);
    }
}
