<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'parent_id' => null, // Example: Pages may not have a parent
            'sort' => $this->faker->unique()->randomNumber(),
            // Add any other attributes here
        ];
    }

    public function home()
    {
        return $this->afterCreating(function (Page $page) {
            foreach (config('app.locales') as $locale) {
                $page->translateOrNew($locale)->title = 'Home';
                $page->translateOrNew($locale)->slug = $this->faker->slug(); // Append a random string for uniqueness
                $page->translateOrNew($locale)->keywords = 'Home Page';
                $page->translateOrNew($locale)->desc = $this->faker->paragraph();
                $page->translateOrNew($locale)->active = true;
            }
            $page->save();
        });
    }

    /**
     * Define the model's state for the 'about' page.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function about()
    {
        return $this->afterCreating(function (Page $page) {
            foreach (config('app.locales') as $locale) {
                $page->translateOrNew($locale)->title = 'About Us';
                $page->translateOrNew($locale)->slug = $this->faker->slug(); // Append a random string for uniqueness
                $page->translateOrNew($locale)->keywords = 'about us';
                $page->translateOrNew($locale)->desc = $this->faker->paragraph();
                $page->translateOrNew($locale)->active = true;
            }
            $page->save();
        });
    }

    /**
     * Define the model's state for the 'shop' page.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function shop()
    {
        return $this->afterCreating(function (Page $page) {
            foreach (config('app.locales') as $locale) {
                $page->translateOrNew($locale)->title = 'Shop';
                $page->translateOrNew($locale)->slug = $this->faker->slug(); // Append a random string for uniqueness
                $page->translateOrNew($locale)->keywords = 'shop';
                $page->translateOrNew($locale)->desc = $this->faker->paragraph();
                $page->translateOrNew($locale)->active = true;
            }
            $page->save();
        });
    }

    /**
     * Define the model's state for the 'contact' page.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function contact()
    {
        return $this->afterCreating(function (Page $page) {
            foreach (config('app.locales') as $locale) {
                $page->translateOrNew($locale)->title = 'Contact';
                $page->translateOrNew($locale)->slug = $this->faker->slug(); // Append a random string for uniqueness
                $page->translateOrNew($locale)->keywords = 'contact, get in touch';
                $page->translateOrNew($locale)->desc = $this->faker->paragraph();
                $page->translateOrNew($locale)->active = true;
            }
            $page->save();
        });
    }
}
