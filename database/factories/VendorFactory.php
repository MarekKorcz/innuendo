<?php

use Faker\Generator as Faker;

$factory->define(App\Vendor::class, function (Faker $faker) use ($factory) {
    return [
        'name' => $name = $faker->name,
        'slug' => str_slug($name),
        'description' => $faker->text,
        'email' => $faker->unique()->safeEmail,
        'phone_number' => $faker->phoneNumber,
        'street' => $faker->streetName,
        'street_number' => $faker->numberBetween(0, 100),
        'house_number' => $faker->numberBetween(0, 100),
        'city' => $faker->city,
        'postcode' => $faker->postcode,
        'country' => $faker->country,
        'user_id' => factory(App\User::class)->create()->id
    ];
});