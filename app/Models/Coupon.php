<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'has_expired',
        'expired_on',
        'percent',
        'amount',
        'note',
    ];

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'couponUsers')
                    ->using(CouponUser::class)
                    ->withPivot('id', 'used_on', 'created_at');
    }
}
