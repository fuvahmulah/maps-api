<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Marker;
use Faker\Generator as Faker;
use Grimzy\LaravelMysqlSpatial\Types\Point;

$factory->define(Marker::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'address' => $faker->sentence,
        'district' => $faker->sentence,
        'geometry' => new Point(40.7484404, -73.9878441),
        'marker_type_id' => function () {
            return factory(App\MarkerType::class)->create()->id;
        },
        'created_by' => function () {
            return factory(App\User::class)->create()->id;
        },
        'verified_at' => now(),
        'verified_by' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});
