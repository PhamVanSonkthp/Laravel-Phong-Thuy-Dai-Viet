<?php

namespace Database\Seeders;

use App\Models\NewType;
use Illuminate\Database\Seeder;

class CreateNewTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NewType::firstOrCreate([
            "field" => "App ngày tốt",
        ]);

        NewType::firstOrCreate([
            "field" => "App bán hàng",
        ]);
    }
}
