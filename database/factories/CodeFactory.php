<?php

use Faker\Generator as Faker;

$factory->define(App\Code::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'category' => $faker->country,
        'description' => $faker->sentence,
    ];
});
