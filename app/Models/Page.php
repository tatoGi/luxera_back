<?php

namespace App\Models;

use App\Models\PageOptionsImage;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\PageTypeService;

class Page extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'parent_id',
        'type_id',
        'sort',

    ];

    public $translatedAttributes = ['title', 'locale', 'keywords', 'slug', 'active', 'desc'];
    public function options()
    {
        return $this->hasMany(PageOption::class);
    }
    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id')
            ->with('translation')
            ->with(['children' => function ($query) {
                $query->orderBy('sort', 'asc');
            }]);
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'page_product')
            ->withPivot('sort')
            ->withTimestamps()
            ->orderBy('page_product.sort');
    }

    public function parent()
    {

        return $this->belongsTo(Page::class, 'parent_id')->with('parent.translations');

    }

    public static function rearrange($array)
    {

        self::_rearrange($array, 0);

        \App\Models\Page::all()->each(function ($item) {

            $item->save();

        });

      }

    private static function _rearrange($array, $count, $parent = null)
    {

    foreach ($array as $a) {

        $count++;

        self::where('id', $a['id'])->update(['parent_id' => $parent, 'sort' => $count]);

        if (isset($a['children'])) {

        $count = self::_rearrange($a['children'], $count, $a['id']);

        }

    }

    return $count;

    }
    public function images()
    {
        return $this->hasMany(PageOptionsImage::class);
    }

    public function bannerProducts()
    {
        return $this->belongsToMany(Product::class, 'page_product')
            ->withPivot('sort')
            ->orderBy('page_product.sort');
    }

    public function banners()
    {
        return $this->belongsToMany(Banner::class, 'banner_page')
            ->withPivot('sort')
            ->orderBy('banner_page.sort');
    }

    /**
     * Get all posts for this page
     */
    public function posts()
    {
        return $this->hasMany(Post::class)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get active posts for this page
     */
    public function activePosts()
    {
        return $this->posts()->where('active', true);
    }

    /**
     * Get published posts for this page
     */
    public function publishedPosts()
    {
        return $this->activePosts()->where('published_at', '<=', now());
    }

    /**
     * Check if this page type supports posts
     */
    public function supportsPost()
    {
        $pageType = $this->getPageTypeConfig();
        return $pageType && ($pageType['has_posts'] ?? false);
    }

    /**
     * Get page type configuration
     */
    public function getPageTypeConfig()
    {
        return PageTypeService::getPageTypeConfig($this->type_id);
    }
}
