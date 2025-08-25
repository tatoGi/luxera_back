<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements TranslatableContract
{
    use HasFactory,Translatable,HasSEO;

    protected $fillable = [
        'product_identify_id',
        'category_id',
        'size',
        'price',
        'active',
        'sort_order',
    ];

    public $translatedAttributes = ['title', 'slug', 'description', 'brand', 'location', 'color'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_product')
            ->withPivot('sort')
            ->withTimestamps()
            ->orderBy('page_product.sort');
    }
    public function getDynamicSEOData(): SEOData
    {
        $firstImage = $this->images()->first();

        $ogImage = $firstImage ? asset("storage/{$firstImage->image_name}") : null;

        return new SEOData($ogImage);
    }
}
