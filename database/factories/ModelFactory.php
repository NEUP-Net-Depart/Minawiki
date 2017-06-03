<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
$factory->define(App\Comment::class, function (Faker\Generator $faker) {

    return [
        'user_id'=>$faker->numberBetween(1,10),
        'content'=>$faker->text,
        'page_id'=>$faker->randomNumber(),
        'signature'=>$faker->userName,
        'star_num'=>$faker->randomNumber(),
        'reply_id'=>$faker->numberBetween(15,20),
        'updated_at'=>$faker->dateTime


    ];
});
$factory->define(App\CommentMessage::class, function (Faker\Generator $faker) {

    return [
        'user_id'=>$faker->numberBetween(1,10),
        'comment_id'=>$faker->numberBetween(15,20),
        'is_read'=>$faker->boolean,
        'updated_at'=>$faker->dateTime


    ];
});
$factory->define(App\StarMessage::class, function (Faker\Generator $faker) {

    return [
        'user_id'=>$faker->numberBetween(1,10),
        'comment_id'=>$faker->numberBetween(10,15),
        'times'=>$faker->numberBetween(1,2),
        'is_read'=>$faker->boolean,
        'updated_at'=>$faker->dateTime


    ];
});
