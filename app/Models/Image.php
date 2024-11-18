<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'src',
    ];

    protected function src(): Attribute {
        return Attribute::make(
            get: fn ($value) => url('/storage/'.$value),
        );
    }

    public function space(): BelongsTo {
        return $this->belongsTo(Space::class);
    }

}
