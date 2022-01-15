<?php

use App\Config;
use Illuminate\Database\Seeder;

class ConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!\App\Config::find(1)) {
            Config::create();
        }
    }
}
