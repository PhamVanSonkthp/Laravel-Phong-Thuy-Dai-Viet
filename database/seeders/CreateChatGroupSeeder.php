<?php

namespace Database\Seeders;

use App\Models\ChatGroup;
use App\Models\Logo;
use Illuminate\Database\Seeder;

class CreateChatGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChatGroup::firstOrCreate([
            "title" => "First Chat With Myself",
        ]);
    }
}
