<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'prefix',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'state',
        'amount_due',
        'note',
        'is_gift',
        'receiver_user_id',
        'giver_user_id',
        'reason_for_gift',
    ];

    public function created_by(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function receiver_user_id(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function giver_user_id(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function ressource(): BelongsTo {
        return $this->belongsTo(Ressource::class);
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class);
    }

}
