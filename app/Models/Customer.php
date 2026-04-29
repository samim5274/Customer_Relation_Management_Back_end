<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',//
        'visa_category_id',//
        'name',
        'slug',
        'sku',
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
        'is_submitted',
        'passport_no',
        'passport_expiry_date',
        'passport_photo',
        'national_id_photo',
        'spouse_name',
        'spouse_photo',
        'spouse_nid',
        'spouse_nid_photo',
    ];

    // --- Boot Method for Default Password Hashing ---
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (!$customer->password) {
                $customer->password = Hash::make('password');
            }

            /** =========================
            *  SLUG GENERATE
            * ========================= */
            if (empty($customer->slug)) {
                $baseSlug = Str::slug($customer->name);
                $uniqueSlug = $baseSlug . '-' . Str::lower(Str::random(5));

                while (static::where('slug', $uniqueSlug)->exists()) {
                    $uniqueSlug = $baseSlug . '-' . Str::lower(Str::random(5));
                }
                $customer->slug = $uniqueSlug;
            }

            /** =========================
            *  SKU GENERATE
            * ========================= */
            if (empty($customer->sku)) {
                $prefix = 'CUST';
                $newSku = $prefix . '-' . strtoupper(Str::random(8));

                while (static::where('sku', $newSku)->exists()) {
                    $newSku = $prefix . '-' . strtoupper(Str::random(8));
                }
                $customer->sku = $newSku;
            }
        });
    }

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'dob' => 'date',
        'passport_expiry_date' => 'date',
        'is_active' => 'boolean',
        'is_submitted' => 'boolean',
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

    public function followUp()
    {
        return $this->hasMany(FollowUp::class, 'customer_id');
    }
}
