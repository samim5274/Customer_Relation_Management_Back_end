<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\VisaCategory;
use Illuminate\Support\Facades\DB;

class VisaCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visaCategories = [
            ['name' => 'Tourist Visa', 'is_active' => true],
            ['name' => 'Student Visa', 'is_active' => true],
            ['name' => 'Business Visa', 'is_active' => true],
            ['name' => 'Work Permit Visa', 'is_active' => true],
            ['name' => 'Medical Visa', 'is_active' => true],
            ['name' => 'Transit Visa', 'is_active' => true],
        ];

        foreach ($visaCategories as $category) {
            VisaCategory::create($category);
        }

        
    }
}
