<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMode extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
    ];

    public function payment(): HasMany {
        return $this->hasMany(Payment::class);
    }
}
