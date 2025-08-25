<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'icon' => 'joystick-icon.png',
                'active' => true,
                'translations' => [
                    ['locale' => 'en', 'title' => 'Joysticks', 'slug' => 'joysticks', 'description' => 'Game joysticks'],
                    ['locale' => 'ka', 'title' => 'კონსოლები', 'slug' => 'კონსოლები', 'description' => 'სათამაშო კონსოლები']
                ],
            ],
            [
                'icon' => 'keyboard-icon.png',
                'active' => true,
                'translations' => [
                    ['locale' => 'en', 'title' => 'Keyboards', 'slug' => 'keyboards', 'description' => 'Gaming keyboards'],
                    ['locale' => 'ka', 'title' => 'კლავიატურა', 'slug' => 'კლავიატურა', 'description' => 'კლავიატურა']
                ],
            ],
            [
                'icon' => 'mouse-icon.png',
                'active' => true,
                'translations' => [
                    ['locale' => 'en', 'title' => 'Mice', 'slug' => 'mice', 'description' => 'Gaming mice'],
                    ['locale' => 'ka', 'title' => 'მაუსები', 'slug' => 'მაუსები', 'description' => 'მაუსები']
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'icon' => $categoryData['icon'],
                'active' => $categoryData['active']
            ]);

            foreach ($categoryData['translations'] as $translation) {
                $category->translations()->create($translation);
            }
        }
    }
}
