<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity_log extends Model
{
    use HasFactory;
    protected $fillable = [
        'description', 'url', 'method', 'ip', 'agent', 'user_id'
    ];

    protected $sortable = [
        'description', 'user', 'created_at', 'method'
    ];
}
