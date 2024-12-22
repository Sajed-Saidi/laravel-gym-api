<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Api\Booking;
use App\Models\Api\Subscription;
use App\Models\Api\Trainer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function bookings(): HasMany
    {
        return  $this->hasMany(Booking::class);
    }

    public function trainer(): HasOne
    {
        return $this->hasOne(Trainer::class);
    }

    public static function accessMembers()
    {
        return User::where('role', 'member')->whereHas('subscription')->get();
    }
    public static function trainers()
    {
        return User::where('role', 'trainer')->get();
    }
}
