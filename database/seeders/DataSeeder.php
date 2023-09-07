<?php

namespace Database\Seeders;

use App\Models\AutoAdd;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AutoAdd::create([
            'date' => "2023-09-01"
        ]);
    }
}
