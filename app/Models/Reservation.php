<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        /*
        'is_gift',
        'receiver_user_id',
        'giver_user_id',
        'reason_for_gift',
        */
        'prefix',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'state',
        'amount_due',
        'note_en',
        'note_fr',
        'cancelled_by',
        'cancelled_at'
    ];

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function cancelledBy(): BelongsTo {
        return $this->belongsTo(User::class, 'cancelled_by', 'id');
    }

    public function ressource(): BelongsTo {
        return $this->belongsTo(Ressource::class);
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class);
    }

    public function couponUsers(): BelongsToMany {
        return $this->belongsToMany(CouponUser::class);
    }
    /*
    public function receiverUser(): BelongsTo {
        return $this->belongsTo(User::class, 'receiver_user_id', 'id');
    }

    public function giverUser(): BelongsTo {
        return $this->belongsTo(User::class, 'giver_user_id', 'id');
    }
        */


}
