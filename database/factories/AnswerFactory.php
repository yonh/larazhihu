<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Answer;
use Faker\Generator as Faker;

$factory->define(Answer::class, function (Faker $faker) {
    return [
        'user_id'=> function() {
            return factory(\App\Models\User::class)->create()->id;
        },
        'question_id'=> function() {
            return factory(\App\Models\Question::class)->create()->id;
        },
        'content' => $faker->text
    ];
});
