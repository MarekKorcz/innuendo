<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->count(1)->create();
        // \App\Models\Property::factory()->count(5)->create();
    }
}
