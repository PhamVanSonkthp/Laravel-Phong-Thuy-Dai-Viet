<?php

namespace Database\Seeders;

use App\Models\SingleImage;
use Illuminate\Database\Seeder;

class CreateLogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SingleImage::create([
            'relate_id' => 1,
            'table' => 'logos',
            'image_path' => '/assets/single/1/original/logo.png',
            'image_name' => 'Logo',
        ]);
    }
}
