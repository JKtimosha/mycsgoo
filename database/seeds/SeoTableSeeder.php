<?php

use Illuminate\Database\Seeder;

class SeoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!\App\Seo::find(1)) {
            \App\Seo::create([
                'name' => 'index',
                'title' => null,
                'description' => null,
                'keywords' => null
            ]);
            \App\Seo::create([
                'name' => 'contracts',
                'title' => null,
                'description' => null,
                'keywords' => null
            ]);
            \App\Seo::create([
                'name' => 'battles',
                'title' => null,
                'description' => null,
                'keywords' => null
            ]);
            \App\Seo::create([
                'name' => 'upgrade',
                'title' => null,
                'description' => null,
                'keywords' => null
            ]);
            \App\Seo::create([
                'name' => 'faq',
                'title' => null,
                'description' => null,
                'keywords' => null
            ]);
            \App\Seo::create([
                'name' => 'top',
                'title' => null,
                'description' => null,
                'keywords' => null
            ]);
            \App\Seo::create([
                'name' => 'case',
                'title' => null,
                'description' => null,
                'keywords' => null
            ]);
            \App\Seo::create([
                'name' => 'agreement',
                'title' => null,
                'description' => null,
                'keywords' => null
            ]);
            \App\Seo::create([
                'name' => 'contacts',
                'title' => null,
                'description' => null,
                'keywords' => null
            ]);
            \App\Seo::create([
                'name' => 'user',
                'title' => null,
                'description' => null,
                'keywords' => null
            ]);
        }
    }
}
