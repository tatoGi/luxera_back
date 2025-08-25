<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    use HasFactory,HasSlug,HasSEO;

    protected $fillable = [
        'title', 'locale', 'keywords', 'slug', 'active', 'desc',

    ];
    public function getSlugOptions(): SlugOptions
    {
        $slugOptions = SlugOptions::createWithLocales(config('app.locales'))
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->usingSeparator('/')
            ->preventOverwrite(); // Prevent overwriting existing slug
    
        return $slugOptions;
    }
    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: $this->description,
        );
    }
}
