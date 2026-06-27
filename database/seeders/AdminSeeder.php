<?php

namespace Database\Seeders;

use App\Models\LoyaltyStamp;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin'],
            [
                'name' => 'admin',
                'surname' => 'admin',
                'password' => Hash::make('adminadmin'),
                'usertype' => 'admin',
                'loyalty_code' => 'ADMIN001',
                'phone' => '+371 00000000',
            ]
        );

        LoyaltyStamp::firstOrCreate(
            ['user_id' => $admin->id],
            ['stamps' => 0]
        );
    }
}
