<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $admin = \App\Models\User::create([
        'name' => 'Admin',
        'surname' => 'Lashbook',
        'email' => 'admin@lashbook.lv',
        'password' => bcrypt('admin123'),
        'usertype' => 'admin',
        'loyalty_code' => 'ADMIN001',
        'phone' => '+371 20000000',
    ]);

    \App\Models\LoyaltyStamp::create([
        'user_id' => $admin->id,
        'stamps' => 0,
    ]);
}
}
