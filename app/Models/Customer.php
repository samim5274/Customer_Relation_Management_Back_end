<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',//
        'visa_category_id',//
        'name',
        'email',
        'phone',
        'password',
        'photo',
        'father_name',
        'mother_name',
        'dob',
        'gender',
        'blood_group',
        'national_id',
        'religion',
        'occupation',
        'is_active',
        'present_address',
        'permanent_address',
        'wallet_balance',
        'is_submited',
        'passport_no',
        'passport_expiry_date',
        'passport_photo',
        'national_id_photo',
        'spouse_name',
        'spouse_photo',
        'spouse_nid',
        'spouse_nid_photo',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'dob' => 'date',
        'passport_expiry_date' => 'date',
        'is_active' => 'boolean',
        'is_submited' => 'boolean',
        'wallet_balance' => 'decimal:2',
    ];

    protected $hidden = [
        'password',
    ];

    // --- Relationships ---
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function visaCategory(): BelongsTo
    {
        return $this->belongsTo(VisaCategory::class, 'visa_category_id');
    }

    // --- Boot Method for Default Password Hashing ---
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (!$customer->password) {
                $customer->password = Hash::make('password');
            }
        });
    }
}
