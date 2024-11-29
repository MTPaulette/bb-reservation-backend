<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Coupon extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        // 'code',
        'total_usage',
        'status',
        'expired_on',
        'percent',
        'amount',
        'note_en',
        'note_fr',
    ];

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'couponUsers')
                    ->using(CouponUser::class)
                    ->withPivot('id', 'created_at');
    }

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
