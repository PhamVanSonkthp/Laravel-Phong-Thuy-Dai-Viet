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
            "name" => "App ngày tốt",
        ]);

        NewType::firstOrCreate([
            "name" => "App bán hàng",
        ]);

        NewType::firstOrCreate([
            "name" => "Tất cả app",
        ]);
    }
}
