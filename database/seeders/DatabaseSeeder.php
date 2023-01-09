<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(PaymentProviderSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(LotteryTimeSeeder::class);
        $this->call(ThreeDigitSeeder::class);
        $this->call(TwoDigitSeeder::class);
        $this->call(LimitAmountSeeder::class);
        $this->call(TwoDigitCompensationSeeder::class);
        $this->call(ThreeDigitCompensationSeeder::class);
        $this->call(EnabledSeeder::class);
        $this->call(DisableSeeder::class);
        

       

        $this->call(MaungLimitSeeder::class);
        $this->call(FootballBodySeeder::class);
        $this->call(BalloneSeeder::class);
    }
}
