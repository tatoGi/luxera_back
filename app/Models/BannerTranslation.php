<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class BannerTranslation extends Model
{
    use HasFactory,HasSlug,HasSeo;

    protected $fillable = [
        'title',
        'slug',
        'desc',
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
