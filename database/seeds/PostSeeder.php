<?php

use App\Enums\FriendStatus;
use App\Friends;
use App\Post;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = factory(Post::class, 15)->make();
        foreach ($posts as $post) {
            $post->save();
        }
    }
}
