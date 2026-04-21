<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_name',
        'shop_slug',
        'shop_logo',
        'shop_logo_2',
        'shop_description',
        'vendor_status',
        'is_active',
        'wallet_balance',
        'commission_rate',
        'tax_id',
        'business_license',
        'business_document',
        'email',
        'phone',
        'emergency_phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'featured',
        'rating',
        'total_products',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'featured' => 'boolean',
        'wallet_balance' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'rating' => 'integer',
        'total_products' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function scopeApproved($query)
    {
        return $query->where('vendor_status', 'approved')->where('is_active', true);
    }
}
