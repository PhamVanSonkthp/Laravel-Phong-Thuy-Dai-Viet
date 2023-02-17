<?php

namespace Database\Seeders;

use App\Models\ChatGroup;
use App\Models\Logo;
use App\Models\StatusImage;
use Illuminate\Database\Seeder;

class CreateStatusImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusImage::firstOrCreate([
            "name" => "Công khai",
        ]);

        StatusImage::firstOrCreate([
            "name" => "Riêng tư",
        ]);
    }
}
