<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

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

    protected function password(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => Hash::make($value),
        );
    }

    public function created_by(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    
    public function users(): HasMany {
        return $this->hasMany(User::class);
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
                    ->withPivot('id', 'nb_usage', 'used_on', 'created_at');
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

    /*============================================ */

    public function permissions() {
        return $this->role->permissions();
    }

    public function hasPermission($permission) {
        // return $this->permissions()->contains($permission);
        return $this->permissions()->where("name", $permission)->exists();
    }

    public function role(): BelongsTo {
        return $this->belongsTo(Role::class);
    }
    public function scopeWithRole(Builder $query): Builder{
        return $query->join('roles', 'users.role_id', '=', 'roles.id')
                    ->select('users.*', 'roles.name as role')
                    ->orderByDesc('created_at');
    }
}
