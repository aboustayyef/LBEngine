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

use \Carbon\Carbon;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Channel::class, function (Faker\Generator $faker) {
    return [
        'shorthand' => $faker->unique()->word,
        'description' => $faker->sentence
    ];
});

$factory->define(App\Source::class, function (Faker\Generator $faker) {
    return [
        'shorthand' => $faker->unique()->word,
        'name'  => $faker->catchPhrase,
        'description' => $faker->paragraph(2),
        'homepage' => $faker->url,
        'rss_feed' => $faker->url . '/feed',
        'author'    => $faker->name,
        'author_twitter' =>$faker->word,
        'author_email'  => $faker->email,
    ];
});

$factory->define(App\Post::class, function (Faker\Generator $faker) {
    return [
        'title'                 => $faker->catchPhrase,
        'excerpt'               => $faker->paragraph(2),
        'url'                   => $faker->url,
        'publishing_date'       => Carbon::now()->format('Y-m-d H:i:s'),
        'content'               => $faker->paragraphs(3,true),
    ];
});

/*

$table->text('content')->nullable();
 */