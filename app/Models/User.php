<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'u_id', 'phone', 'password', 'photo',
        'dob', 'gender', 'blood_group', 'national_id', 'religion',
        'role',
        'is_active', 'is_profile_completed',
        'present_address', 'permanent_address',
        'otp', 'otp_expires_at',
        'last_login_at', 'last_login_ip',
        'wallet_balance',
        'tokens',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'dob' => 'date',
        'is_active' => 'boolean',
        'is_profile_completed' => 'boolean',
        'wallet_balance' => 'decimal:2',
        'password' => 'hashed',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->password)) {
                // $user->password = Hash::make('password');
                $user->password = Hash::make(bin2hex(random_bytes(4)));
            }

            $attempt = 0;

            do {
                $userId = 'UID-' . strtoupper(Str::random(10));
                $attempt++;
            } while (User::where('u_id', $userId)->exists() && $attempt < 5);

            $user->u_id = $userId;
        });
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
