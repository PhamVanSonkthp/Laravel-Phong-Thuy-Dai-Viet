<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CreateUserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserStatus::firstOrCreate([
            "name" => "Hoạt động",
        ]);

        UserStatus::firstOrCreate([
            "name" => "Khóa",
        ]);
    }
}
