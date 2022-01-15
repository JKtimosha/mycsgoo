<?php

use Illuminate\Database\Seeder;

class CasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!\App\Category::find(1)) {
            \App\Category::create([
                'name_en' => 'Mini-games',
                'name' => 'Мини-игры',
                'number' => 1
            ]);
        }

        if (!\App\Cases::find(1)) {
            \App\Cases::create([
                'name' => 'Контракты',
                'name_en' => 'Contracts',
                'name_url' => 'contracts',
                'category_id' => 1,
                'image' => '/cases/contracts.png',
                'old_price' => 0,
                'price' => 0,
                'price_en' => 0.00,
                'type' => 'limited',
                'max_open' => 0,
                'open' => 0
            ]);
        }

        if (!\App\Cases::find(2)) {
            \App\Cases::create([
                'name' => 'Апгрейд',
                'name_en' => 'Upgrade',
                'name_url' => 'upgrade',
                'category_id' => 1,
                'image' => '/cases/upgrade.png',
                'old_price' => 0,
                'price' => 0,
                'price_en' => 0.00,
                'type' => 'limited',
                'max_open' => 0,
                'open' => 0
            ]);
        }
    }
}
