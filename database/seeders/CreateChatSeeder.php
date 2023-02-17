<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\ChatGroup;
use App\Models\Logo;
use Illuminate\Database\Seeder;

class CreateChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Chat::firstOrCreate([
            "content" => "The First Chat",
            "user_id" => "1",
            "chat_group_id" => "1",
        ]);
    }
}
