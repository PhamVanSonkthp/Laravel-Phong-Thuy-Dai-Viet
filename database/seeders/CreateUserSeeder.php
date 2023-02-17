<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'phone' => 'admin',
                'email' => 'admin',
                'password' => Hash::make("1111"),
                'is_admin'=> 2,
                'role_id'=> 1,
            ],
            [
                'name' => 'Phạm văn sơn',
                'phone' => '0378115213',
                'email' => 'bontukyhpkt@gmail.com',
                'password' => Hash::make("1111"),
                'is_admin'=> 0,
                'role_id'=> 0,
            ],
        ]);
    }
}
