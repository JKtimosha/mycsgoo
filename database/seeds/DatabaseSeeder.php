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
        $this->call([
            ConfigsTableSeeder::class,
            StylesTableSeeder::class,
            CasesTableSeeder::class,
            SeoTableSeeder::class
        ]);
    }
}
