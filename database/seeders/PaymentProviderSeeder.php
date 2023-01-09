<?php

namespace Database\Seeders;

use App\Models\PaymentProvider;
use Illuminate\Database\Seeder;

class PaymentProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentProvider::create([
            'name' => 'Wave Money',
            'owner' => 'Test',
            'phone_number' => '09123456789',
            'image' => 'payment/wave.png'
        ]);

        PaymentProvider::create([
            'name' => 'KBZ Pay',
            'owner' => 'Test',
            'phone_number' => '09123456789',
            'image' => 'payment/kpay.png'
        ]);
    }
}
