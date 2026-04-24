<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Customer;
use App\Models\Country;
use App\Models\VisaCategory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $countryIds = Country::pluck('id')->toArray();
        $visaCategoryIds = VisaCategory::pluck('id')->toArray();

        if (empty($countryIds) || empty($visaCategoryIds)) {
            $this->command->info('Please seed Countries and VisaCategories first!');
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            Customer::create([
                'country_id'           => $faker->randomElement($countryIds),
                'visa_category_id'     => $faker->randomElement($visaCategoryIds),
                'name'                 => $faker->name,
                'email'                => $faker->unique()->safeEmail,
                'phone'                => $faker->unique()->numerify('###########'),
                'password'             => Hash::make('password'),
                'photo'                => null,
                'father_name'          => $faker->name('male'),
                'mother_name'          => $faker->name('female'),
                'dob'                  => $faker->date('Y-m-d', '2000-01-01'),
                'gender'               => $faker->randomElement(['Male', 'Female', 'Other']),
                'blood_group'          => $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
                'national_id'          => $faker->unique()->numerify('###########'),
                'religion'             => $faker->randomElement(['Islam', 'Hinduism', 'Christianity', 'Buddhism']),
                'occupation'           => $faker->jobTitle,
                'is_active'            => true,
                'present_address'      => $faker->address,
                'permanent_address'    => $faker->address,
                'wallet_balance'       => $faker->randomFloat(2, 100, 5000),
                'is_submitted'          => $faker->boolean,
                'passport_no'          => strtoupper($faker->bothify('??#######')),
                'passport_expiry_date' => $faker->dateTimeBetween('+1 year', '+10 years'),
                'passport_photo'       => null,
                'national_id_photo'    => null,
                'spouse_name'          => $faker->name,
                'spouse_nid'           => $faker->numerify('###########'),
            ]);
        }
    }
}
