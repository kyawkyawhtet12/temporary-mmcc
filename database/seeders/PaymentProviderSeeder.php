<?php

namespace Database\Seeders;

use App\Models\AdminWithdrawalAccount;
use App\Models\Payment;
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
            'image' => 'payment/wave.png',
            'agent_id' => 1,
        ]);

        PaymentProvider::create([
            'name' => 'KBZ Pay',
            'owner' => 'Test',
            'phone_number' => '09123456789',
            'image' => 'payment/kpay.png',
            'agent_id' => 1
        ]);

        AdminWithdrawalAccount::create([
            'name' => 'KBZ Pay'
        ]);

        AdminWithdrawalAccount::create([
            'name' => 'Wave Pay'
        ]);

        // Payment::create([
        //     'amount' => 100000,
        //     'user_id' => 1,
        //     'payment_provider_id' => 1,
        //     'phone' => '09123456789',
        //     'transation_no' => '000000',
        //     'status' => 'Approved',
        //     'agent_id' => 1
        // ]);
    }
}
