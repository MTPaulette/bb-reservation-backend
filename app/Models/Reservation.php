<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;


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
        'start_date',
        'end_date',
        'start_hour',
        'end_hour',
        'initial_amount',
        'amount_due',
        'state',
        'note',
        'reason_for_cancellation',
        'cancelled_by',
        'cancelled_at'
    ];

    public function ressource(): BelongsTo {
        return $this->belongsTo(Ressource::class);
    }

    public function client(): BelongsTo {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function coupon(): BelongsTo {
        return $this->belongsTo(Coupon::class);
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class);
    }

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function cancelledBy(): BelongsTo {
        return $this->belongsTo(User::class, 'cancelled_by', 'id');
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
