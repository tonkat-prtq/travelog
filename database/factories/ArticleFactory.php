<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;

use App\User;

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->text(50),
        'content' => $faker->text(500),
        'start_date' => Carbon::today(),
        'end_date' => Carbon::today('+1 day'),
        'user_id' => function () {
            return factory(User::class);
        },
    ];
});
