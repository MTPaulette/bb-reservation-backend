<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        // 'description'
    ];

    public function users(): HasMany {
        return $this->hasMany(User::class);
    }

    public function permissions(): BelongsToMany {
        return $this->belongsToMany(Permission::class);
    }
}
