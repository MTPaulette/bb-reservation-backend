<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'bill_number',
    ];

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }

    public function processedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'processed_by', 'id');
    }
}
