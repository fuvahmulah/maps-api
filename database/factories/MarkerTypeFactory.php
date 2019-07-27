<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\MarkerType;
use Faker\Generator as Faker;

$factory->define(MarkerType::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'icon' => $faker->sentence,
        'created_by' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});
