<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class ProductTranslation extends Model
{
    use HasFactory, HasSlug, HasSEO;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'brand', 
        'location',
        'color',
        'warranty_period'
    ];

    // Disable timestamps
    public $timestamps = false;

    public function getSlugOptions(): SlugOptions
    {
        $slugOptions = SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->usingSeparator('-')
            ->preventOverwrite(); // Prevent overwriting existing slug

        return $slugOptions;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getDynamicSEOData(): SEOData
    {
        $ogImage = $this->getOgImage();
        
        return new SEOData(
            title: $this->title,
            description: $this->description,
            og_image: $ogImage,
        );
    }

    public function getOgImage(): ?string
    {
        $product = $this->product;
        dd($product);
        if (!$product) {
            return null;
        }
    
        $firstImage = $product->images()->first();
    
        if (!$firstImage) {
            return null;
        }
    
        return $firstImage->getImageUrl(); 
    }
}
