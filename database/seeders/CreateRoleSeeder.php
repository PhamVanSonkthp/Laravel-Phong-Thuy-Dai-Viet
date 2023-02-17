<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'Admin', 'display_name'=>'Quản trị hệ thống', 'content'=> ''],
            ['name' => 'Nhân viên', 'display_name'=>'Quản lý khách hàng', 'content'=> ''],
            ['name' => 'Content', 'display_name'=>'Chỉnh sửa nội dung', 'content'=> ''],
        ]);
    }
}
