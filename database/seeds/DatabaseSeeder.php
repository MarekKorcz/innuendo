<?php

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
        factory(App\Vendor::class, 25)->create();
        factory(App\Category::class, 50)->create();
        factory(App\Item::class, 100)->create();
    }
}
