<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sliders')->insert([
            [
                'link' => '/',
                'feature_image_name' => 'Slider',
                'feature_image_path' => '/assets/images/slider/original/Slider-Infinity-Main-min.png'
            ],

        ]);
    }
}
