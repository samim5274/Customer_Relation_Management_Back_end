<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Country;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Bangladesh', 'is_active' => true],
            ['name' => 'United States', 'is_active' => true],
            ['name' => 'United Kingdom', 'is_active' => true],
            ['name' => 'Canada', 'is_active' => true],
            ['name' => 'Australia', 'is_active' => true],
            ['name' => 'Saudi Arabia', 'is_active' => true],
            ['name' => 'United Arab Emirates', 'is_active' => true],
            ['name' => 'Malaysia', 'is_active' => true],
            ['name' => 'Singapore', 'is_active' => true],
            ['name' => 'India', 'is_active' => true],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
