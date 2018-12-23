<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) use ($factory) {
    return [
        'name' => $name = $faker->name,
        'slug' => str_slug($name),
        'description' => $faker->text,
        'image' => $faker->imageUrl(),
        'vendor_id' => rand(1, 25)
    ];
});