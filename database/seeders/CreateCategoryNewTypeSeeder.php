<?php

namespace Database\Seeders;

use App\Models\CategoryNewType;
use App\Models\NewType;
use Illuminate\Database\Seeder;

class CreateCategoryNewTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoryNewType::firstOrCreate([
            "name" => "App ngày tốt",
        ]);

        CategoryNewType::firstOrCreate([
            "name" => "App bán hàng",
        ]);
    }
}
