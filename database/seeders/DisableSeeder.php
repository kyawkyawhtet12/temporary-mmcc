<?php

namespace Database\Seeders;

use App\Models\Disable;
use Illuminate\Database\Seeder;

class DisableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Disable::create([
            'datetime' => "2021-01-15 12:03",
        ]);
    }
}
