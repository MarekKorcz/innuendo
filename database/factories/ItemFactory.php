<?php

use Faker\Generator as Faker;

$factory->define(App\Item::class, function (Faker $faker) use ($factory) {
    return [
        'name' => $name = 'Item '.rand(0, 1000000),
        'slug' => str_slug($name),
        'description' => $faker->text,
        'price' => $faker->numberBetween(8, 150),
        'manufacture_time' => $faker->numberBetween(3, 10),
        'image' => $faker->imageUrl(),
        'category_id' => rand(1, 50)
    ];
});