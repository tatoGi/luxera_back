<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'parent_id' => null,
                'type_id' => 1,
                'sort' => 1,
                'active' => true,
                'translations' => [
                    ['locale' => 'en', 'title' => 'Home', 'keywords' => 'home', 'slug' => 'home', 'active' => true, 'desc' => 'Home Page'],
                    ['locale' => 'ka', 'title' => 'მთავარი', 'keywords' => 'მთავარი', 'slug' => 'მთავარი', 'active' => true, 'desc' => 'მთავარი გვერდი']
                ],
            ],
            [
                'parent_id' => null,
                'type_id' => 4,
                'sort' => 2,
                'active' => true,
                'translations' => [
                    ['locale' => 'en', 'title' => 'About Us', 'keywords' => 'about us', 'slug' => 'about-us', 'active' => true, 'desc' => 'About Us Page'],
                    ['locale' => 'ka', 'title' => 'ჩვენ შესახებ', 'keywords' => 'ჩვენ შესახებ', 'slug' => 'ჩვენ-შესახებ', 'active' => true, 'desc' => 'ჩვენ შესახებ']
                ],
            ],
            [
                'parent_id' => null,
                'type_id' => 2,
                'sort' => 2,
                'active' => true,
                'translations' => [
                    ['locale' => 'en', 'title' => 'Shop', 'keywords' => 'shop', 'slug' => 'shop', 'active' => true, 'desc' => 'Shop Page'],
                    ['locale' => 'ka', 'title' => 'მაღაზია', 'keywords' => 'მაღაზია', 'slug' => 'მაღაზია', 'active' => true, 'desc' => 'მაღაზია']
                ],
            ],
        ];

        foreach ($pages as $pageData) {
            $page = Page::create([
                'parent_id' => $pageData['parent_id'],
                'type_id' => $pageData['type_id'],
                'sort' => $pageData['sort'],
                'active' => $pageData['active']
            ]);

            foreach ($pageData['translations'] as $translation) {
                $page->translations()->create($translation);
            }
        }
    }
}
