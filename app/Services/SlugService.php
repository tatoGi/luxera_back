<?php
namespace App\Services;

use App\Models\CategoryTranslation;
use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;

class SlugService
{
    /**
     * Generate slug options using Spatie's sluggable package.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(string $value): string
    {
        $slugOptions = SlugOptions::createWithLocales(config('app.locales'))
            ->generateSlugsFrom(function ($model, $locale) {
                return $model->title[$locale]; // Assuming your model has a title attribute
            })
            ->saveSlugsTo('slug')
            ->usingSeparator('/');

            return Str::slug($value, $slugOptions);
    }
    
}
