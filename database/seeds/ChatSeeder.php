<?php

use App\Chat;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chats = factory(Chat::class, 5)->make([
            "user_a_id" => 1,
            "user_b_id" => 2
        ]);
        foreach ($chats as $chat) {
            $chat->save();
        }
        $chats = factory(Chat::class, 5)->make([
            "user_a_id" => 2,
            "user_b_id" => 1
        ]);
        foreach ($chats as $chat) {
            $chat->save();
        }
    }
}
