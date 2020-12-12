<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    $rand = rand(0, count(Post::$sampleParagragh) - 1);
    return [
        "user_id" => rand(1, 3),
        "described" => Post::$sampleParagragh[$rand]
    ];
});
