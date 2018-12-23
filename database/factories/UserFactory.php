<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt("secret")
    ];
});

//$factory->define(App\Vendor::class, function (Faker $faker) use ($factory) {
//    return [
//        'name' => $name = $faker->name,
//        'slug' => str_slug($name),
//        'description' => $faker->text,
//        'email' => $faker->unique()->safeEmail,
//        'phone_number' => $faker->phoneNumber,
//        'street' => $faker->streetName,
//        'street_number' => $faker->numberBetween(0, 100),
//        'house_number' => $faker->numberBetween(0, 100),
//        'city' => $faker->city,
//        'postcode' => $faker->postcode,
//        'country' => $faker->country,
//        'user_id' => factory(App\User::class)->create()->id
//    ];
//});

//$factory->define(App\Category::class, function (Faker $faker) use ($factory) {
//    return [
//        'name' => $name = $faker->name,
//        'slug' => str_slug($name),
//        'description' => $faker->text,
//        'image' => $faker->imageUrl(),
//        'vendor_id' => factory(App\Vendor::class)->create()->id
//    ];
//});