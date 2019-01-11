<?php

use Faker\Generator as Faker;

$admin = $factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt("secret"),
        'remember_token' => str_random(10),
        'isAdmin' => 1
    ];
});

$factory->define(App\Property::class, function (Faker $faker) use ($factory) {
    return [
        'name' => $name = 'Property '.rand(0, 1000000),
        'slug' => str_slug($name),
        'description' => $faker->text,
        'phone_number' => $faker->phoneNumber,
        'street' => $faker->streetName,
        'street_number' => $faker->numberBetween(0, 100),
        'house_number' => $faker->numberBetween(0, 100),
        'city' => $faker->city,
        'user_id' => factory(App\User::class)->create()->id
    ];
});