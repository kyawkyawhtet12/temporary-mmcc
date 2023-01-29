<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'is_admin' => 1
        ]);

        Admin::create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'password' => bcrypt('password'),
            'is_admin' => 0
        ]);

        Agent::create([
            'name' => 'Agent One',
            'phone' => '09123456789',
            'amount' => 10000,
            'referral_code' => 'test',
            'password' => bcrypt('password'),
            'percentage' => 10
        ]);

        Agent::create([
            'name' => 'Agent Two',
            'phone' => '09123456788',
            'amount' => 10000,
            'referral_code' => 'test1',
            'password' => bcrypt('password'),
            'percentage' => 10
        ]);

        User::create([
            'name' => 'User',
            'user_id' => 'User123',
            'amount' => 100000,
            'referral_code' => 'test',
            'password' => bcrypt('password')
        ]);
    }
}
