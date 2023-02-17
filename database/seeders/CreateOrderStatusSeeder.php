<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class CreateOrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatus::firstOrCreate([
            "name" => "Chờ xác nhận",
        ]);

        OrderStatus::firstOrCreate([
            "name" => "Đang giao",
        ]);

        OrderStatus::firstOrCreate([
            "name" => "Hoàn thành",
        ]);

        OrderStatus::firstOrCreate([
            "name" => "Hủy",
        ]);

        OrderStatus::firstOrCreate([
            "name" => "Hoàn tiền",
        ]);
    }
}
