<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'lastname',
        'firstname',
        'email',
        'password',
        'phonenumber',
        'image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo {
        return $this->belongsTo(Role::class);
    }

    //create agency
    public function agencies(): HasMany {
        return $this->hasMany(Agency::class);
    }

    public function work_at(): BelongsTo {
        return $this->belongsTo(Agency::class);
    }

    public function coupons(): BelongsToMany {
        return $this->belongsToMany(Coupon::class, 'couponUsers')
                    ->using(CouponUser::class)
                    ->withPivot('id', 'used_on', 'created_at');
    }

    public function ressources(): HasMany {
        return $this->hasMany(Ressource::class);
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

    public function received_gifts(): HasMany {
        return $this->hasMany(Reservation::class);
    }

    public function given_gifts(): HasMany {
        return $this->hasMany(Reservation::class);
    }
}
