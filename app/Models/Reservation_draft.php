<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation_draft extends Model
{
    use HasFactory;
    protected $fillable = [
        'ressource_id',
        'client_id',
        'start_date',
        'end_date',
        'start_hour',
        'end_hour',
        'initial_amount',
        'amount_due',
        'coupon_id',
        'created_by',
    ];
}
