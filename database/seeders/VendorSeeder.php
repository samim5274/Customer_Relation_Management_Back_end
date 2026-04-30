<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vendor::create([
            'shop_name'         => 'Swift Overseas',
            'shop_slug'         => Str::slug('Swift Overseas Tours and Travels'),
            'shop_logo'         => 'logos/swift-overseas.png',
            'shop_description'  => 'Your trusted partner for international travel, visa processing, and holiday packages.',

            // Status
            'vendor_status'     => 'approved',
            'is_active'         => true,

            // Financial
            'wallet_balance'    => 0.00,
            'commission_rate'   => 0.00,

            // Business Info
            'tax_id'            => 'TRAD/DNC/012345/2026',
            'business_license'  => 'BL-998877',

            // Contact
            'email'             => 'info@swiftoverseas.com',
            'phone'             => '+8801700000000',
            'emergency_phone'   => '+8801800000000',

            // Address
            'address'           => 'Level 4, Tropical Alauddin Tower',
            'city'              => 'Uttara',
            'state'             => 'Dhaka',
            'country'           => 'Bangladesh',
            'postal_code'       => '1230',

            // Others
            'featured'          => true,
            'rating'            => 5,
            'total_products'    => 0,
        ]);
    }
}
