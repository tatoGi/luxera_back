<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'parent_id' => null,
            'active' => $this->faker->boolean(),
            'icon' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Category $category) {
            $locales = config('app.locales');
            foreach ($locales as $locale) {
                $category->translateOrNew($locale)->title = $this->faker->words(2, true);
                $category->translateOrNew($locale)->slug = $this->faker->slug();
                $category->translateOrNew($locale)->description = $this->faker->paragraph();
            }
            $category->save();
        });
    }
}
