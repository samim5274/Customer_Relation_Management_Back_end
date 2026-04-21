<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $roles = ['admin', 'manager', 'agent', 'support', 'accounts', 'customer'];

        foreach ($roles as $role) {
            User::create([
                'name'                 => ucfirst($role) . ' User',
                'email'                => $role . '@gmail.com',
                'phone'                => '017' . rand(10000000, 99999999),
                'password'             => Hash::make('321654987'),
                'role'                 => $role,
                'is_active'            => true,
                'is_profile_completed' => true,
                'email_verified_at'    => Carbon::now(),
                'wallet_balance'       => ($role === 'customer') ? 500.00 : 0.00,
                'gender'               => 'Male',
                'blood_group'          => 'O+',
                'present_address'      => 'Dhaka, Bangladesh',
            ]);
        }
    }
}
