<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Fetch the categories to ensure the IDs are correct
        $joystickCategory = Category::whereTranslation('title', 'Joysticks')->first();
        $keyboardCategory = Category::whereTranslation('title', 'Keyboards')->first();
        $mouseCategory = Category::whereTranslation('title', 'Mice')->first();

        // Define the products and their translations
        $products = [
            [
                'category_id' => $joystickCategory->id,
                'price' => 49.99,
                'quantity' => 100,
                'active' => true,
                'translations' => [
                    'en' => ['title' => 'Gaming Joystick', 'slug' => 'gaming-joystick', 'description' => 'High-quality gaming joystick', 'brand' => 'Brand A', 'model' => 'Model X1', 'warranty_period' => '1 year'],
                    'ka' => ['title' => 'სათამაშო ჯოისტიკი', 'slug' => 'სათამაშო-ჯოისტიკი', 'description' => 'მაღალი ხარისხის სათამაშო ჯოისტიკი', 'brand' => 'ბრენდი A', 'model' => 'მოდელი X1', 'warranty_period' => '1 წელი'],
                ],
            ],
            [
                'category_id' => $keyboardCategory->id,
                'price' => 49.99,
                'quantity' => 100,
                'active' => true,
                'translations' => [
                    'en' => ['title' => 'Gaming Joystick', 'slug' => 'gaming-joystick', 'description' => 'High-quality gaming joystick', 'brand' => 'Brand A', 'model' => 'Model X1', 'warranty_period' => '1 year'],
                    'ka' => ['title' => 'სათამაშო ჯოისტიკი', 'slug' => 'სათამაშო-ჯოისტიკი', 'description' => 'მაღალი ხარისხის სათამაშო ჯოისტიკი', 'brand' => 'ბრენდი A', 'model' => 'მოდელი X1', 'warranty_period' => '1 წელი'],
                ],
            ],
            [
                'category_id' => $mouseCategory->id,
                'price' => 49.99,
                'quantity' => 100,
                'active' => true,
                'translations' => [
                    'en' => ['title' => 'Gaming Joystick', 'slug' => 'gaming-joystick', 'description' => 'High-quality gaming joystick', 'brand' => 'Brand A', 'model' => 'Model X1', 'warranty_period' => '1 year'],
                    'ka' => ['title' => 'სათამაშო ჯოისტიკი', 'slug' => 'სათამაშო-ჯოისტიკი', 'description' => 'მაღალი ხარისხის სათამაშო ჯოისტიკი', 'brand' => 'ბრენდი A', 'model' => 'მოდელი X1', 'warranty_period' => '1 წელი'],
                ],
            ],
            [
                'category_id' => $mouseCategory->id,
                'price' => 49.99,
                'quantity' => 100,
                'active' => true,
                'translations' => [
                    'en' => ['title' => 'Gaming Joystick', 'slug' => 'gaming-joystick', 'description' => 'High-quality gaming joystick', 'brand' => 'Brand A', 'model' => 'Model X1', 'warranty_period' => '1 year'],
                    'ka' => ['title' => 'სათამაშო ჯოისტიკი', 'slug' => 'სათამაშო-ჯოისტიკი', 'description' => 'მაღალი ხარისხის სათამაშო ჯოისტიკი', 'brand' => 'ბრენდი A', 'model' => 'მოდელი X1', 'warranty_period' => '1 წელი'],
                ],
            ],
            [
                'category_id' => $mouseCategory->id,
                'price' => 49.99,
                'quantity' => 100,
                'active' => true,
                'translations' => [
                    'en' => ['title' => 'Gaming Joystick', 'slug' => 'gaming-joystick', 'description' => 'High-quality gaming joystick', 'brand' => 'Brand A', 'model' => 'Model X1', 'warranty_period' => '1 year'],
                    'ka' => ['title' => 'სათამაშო ჯოისტიკი', 'slug' => 'სათამაშო-ჯოისტიკი', 'description' => 'მაღალი ხარისხის სათამაშო ჯოისტიკი', 'brand' => 'ბრენდი A', 'model' => 'მოდელი X1', 'warranty_period' => '1 წელი'],
                ],
            ],
            // Add more products similarly
        ];

        // Insert the products into the database
        foreach ($products as $productData) {
            $translations = $productData['translations'];
            unset($productData['translations']);

            $product = Product::create($productData);

            foreach ($translations as $locale => $translation) {
                $product->translateOrNew($locale)->fill($translation);
            }

            $product->save();

            // Add fake images to the product
            for ($i = 0; $i < 3; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_name' => 'fake_image_' . ($i + 1) . '.jpg', // Replace with actual image names or use a faker library for dummy images
                ]);
            }
        }
    }
}
