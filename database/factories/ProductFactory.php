<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'category_id' => function () {
                return \App\Models\Category::factory()->create()->id;
            },
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'rating' => $this->faker->numberBetween(1, 5),
            'active' => $this->faker->boolean(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $locales = config('app.locales');
            foreach ($locales as $locale) {
                $product->translateOrNew($locale)->title = $this->faker->words(3, true);
                $product->translateOrNew($locale)->slug = $this->faker->slug();
                $product->translateOrNew($locale)->description = $this->faker->paragraph();
            }
            $product->save();
        });
    }
}
