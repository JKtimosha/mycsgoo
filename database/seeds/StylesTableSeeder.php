<?php

use App\Style;
use Illuminate\Database\Seeder;

class StylesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!\App\Style::find(1)) {
            Style::create(['type' => 'Ширпотреб', 'style' => 'consumer', 'number' => 1]);
            Style::create(['type' => 'Промышленное качество', 'style' => 'industrial', 'number' => 2]);
            Style::create(['type' => 'Армейское качество', 'style' => 'milspec', 'number' => 3]);
            Style::create(['type' => 'Запрещённое', 'style' => 'restricted', 'number' => 4]);
            Style::create(['type' => 'Засекреченное', 'style' => 'classified', 'number' => 5]);
            Style::create(['type' => 'Тайное', 'style' => 'covert', 'number' => 6]);
            Style::create(['type' => '★', 'style' => 'knife', 'number' => 7]);
        }
    }
}
