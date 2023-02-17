<?php

namespace Database\Seeders;

use App\Models\ChatGroup;
use App\Models\Logo;
use App\Models\ParticipantChat;
use Illuminate\Database\Seeder;

class CreateParticipantChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ParticipantChat::firstOrCreate([
            "user_id" => "1",
            "chat_group_id" => "1",
        ]);

        ParticipantChat::firstOrCreate([
            "user_id" => "2",
            "chat_group_id" => "1",
        ]);
    }
}
