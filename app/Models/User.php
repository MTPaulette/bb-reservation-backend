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
        'status',
        'reason_for_suspension_en',
        'reason_for_suspension_fr',
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
/*
    protected function image(): Attribute {
        return Attribute::make(
            get: fn ($value) => asset('/storage/'.$value),
        );
    }*/
    public function users(): HasMany {
        return $this->hasMany(User::class, 'created_by', 'id');
    }

    public function agencies(): HasMany {
        return $this->hasMany(Agency::class, 'created_by', 'id');
    }

    public function workAt(): BelongsTo {
        return $this->belongsTo(Agency::class, 'work_at', 'id');
    }

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function suspendedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'suspended_by', 'id');
    }

    public function coupons(): BelongsToMany {
        return $this->belongsToMany(Coupon::class, 'couponUsers')
                    ->using(CouponUser::class)
                    ->withPivot('id', 'nb_usage', 'used_on', 'created_at');
    }

    public function ressources(): HasMany {
        return $this->hasMany(Ressource::class, 'created_by', 'id');
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class, 'created_by', 'id');
    }

    public function cancelledReservations(): HasMany {
        return $this->hasMany(Reservation::class, 'cancelled_by', 'id');
    }

    public function suspendedAgencies(): HasMany {
        return $this->hasMany(Agency::class, 'suspended_by', 'id');
    }

    public function suspendedUsers(): HasMany {
        return $this->hasMany(User::class, 'suspended_by', 'id');
    }
    /*
    public function receivedGifts(): HasMany {
        return $this->hasMany(Reservation::class, 'giver_user_id', 'id');
    }

    public function givenGifts(): HasMany {
        return $this->hasMany(Reservation::class, 'receiver_user_id', 'id');
    }
    */
    /*============================================ */

    public function permissions() {
        return $this->role->permissions();
    }

    public function hasPermission($permission) {
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

    public function scopeWithAgency(Builder $query): Builder{
        return $query->join('agencies', 'users.work_at', '=', 'agencies.id')
                    ->select('users.*', 'agencies.name as agency')
                    ->orderByDesc('created_at');
    }

    public function scopeWithAgencyAndRole(Builder $query): Builder{
        // return $query->join('agencies', 'users.work_at', '=', 'agencies.id')
        return $query->join('agencies', 'users.work_at', '=', 'agencies.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->select('users.*', 'agencies.name as agency', 'roles.name as role')
                    ->orderByDesc('created_at');
    }
}
