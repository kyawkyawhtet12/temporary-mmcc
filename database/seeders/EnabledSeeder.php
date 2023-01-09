<?php

namespace Database\Seeders;

use App\Models\Enabled;
use Illuminate\Database\Seeder;

class EnabledSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Enabled::create([
            'two_thai_status' => '0',
            'two_dubai_status' => '0',
            'three_status' => '0',
        ]);
    }
}
