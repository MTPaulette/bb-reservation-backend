<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Validity extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function ressources(): HasMany {
        return $this->hasMany(Ressource::class);
    }
}
