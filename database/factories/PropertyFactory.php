<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        $name = 'Property ' . rand(0, 1000000);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->text(),
            'street' => fake()->streetName(),
            'street_number' => fake()->numberBetween(0, 100),
            'house_number' => fake()->numberBetween(0, 100),
            'city' => fake()->city(),
            'boss_id' => 1,
        ];
    }
}
