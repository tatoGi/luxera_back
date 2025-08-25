<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\Sluggable\SlugOptions;
class CategoryTranslation extends Model
{
    use HasFactory,HasSlug,HasSeo;

    public $timestamps = false;

    protected $fillable = ['locale', 'title', 'slug', 'description'];
    public function getSlugOptions(): SlugOptions
    {
        $slugOptions = SlugOptions::createWithLocales(config('app.locales'))
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->usingSeparator('/')
            ->doNotGenerateSlugsOnUpdate() // Disable slug generation on update
            ->preventOverwrite(); // Prevent overwriting existing slug
    
        return $slugOptions;
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function getDynamicSEOData(): SEOData
    {
        
        return new SEOData(
            title: $this->title,
            description: $this->description,
        );
    }
}
