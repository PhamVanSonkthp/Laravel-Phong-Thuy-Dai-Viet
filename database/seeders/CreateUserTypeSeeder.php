<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class CreateUserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserType::firstOrCreate([
            "name" => "Khách lẻ",
        ]);

        UserType::firstOrCreate([
            "name" => "Đại lý",
        ]);

        UserType::firstOrCreate([
            "name" => "Cộng tác viên",
        ]);
    }
}
