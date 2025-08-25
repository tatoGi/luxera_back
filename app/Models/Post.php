<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\PageTypeService;
use App\Models\Page;
use App\Models\Category;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'category_id',
        'active',
        'sort_order',
        'published_at'
    ];

    protected $casts = [
        'active' => 'boolean',
        'published_at' => 'datetime'
    ];

    // Explicitly define primary key
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Get the page that owns this post
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the category that owns this post
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all attributes for this post
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(PostAttribute::class);
    }

    /**
     * Get all translations for this post
     */
    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    /**
     * Get attribute value by key and locale
     */
    public function getAttribute($key)
    {
        // Handle core model attributes (including id, timestamps, etc.)
        if (in_array($key, $this->fillable) || 
            $key === 'id' || 
            $key === 'created_at' || 
            $key === 'updated_at' ||
            in_array($key, array_keys($this->casts))) {
            return parent::getAttribute($key);
        }

        // Handle relationships - CRITICAL: Let Eloquent handle relationships
        if (method_exists($this, $key) && $this->isPostRelation($key)) {
            return parent::getAttribute($key);
        }

        // Handle dynamic attributes only if post exists
        if (!$this->exists) {
            return null; // Don't try to load dynamic attributes for unsaved models
        }

        // Check if it's a dynamic attribute
        $pageType = $this->getPageTypeConfig();
        if ($pageType && $this->isTranslatableAttribute($key, $pageType)) {
            return $this->getTranslatableAttribute($key);
        }

        return $this->getNonTranslatableAttribute($key);
    }

    /**
     * Check if a method is a Post relationship
     */
    protected function isPostRelation($method)
    {
        $relationMethods = ['page', 'category', 'attributes', 'translations'];
        return in_array($method, $relationMethods);
    }

    /**
     * Set attribute value
     */
    public function setAttribute($key, $value)
    {
        // Handle core model attributes (including id, timestamps, etc.)
        if (in_array($key, $this->fillable) || 
            $key === 'id' || 
            $key === 'created_at' || 
            $key === 'updated_at' ||
            in_array($key, array_keys($this->casts))) {
            return parent::setAttribute($key, $value);
        }

        // Handle dynamic attributes only if post exists
        if (!$this->exists) {
            return; // Don't handle dynamic attributes for unsaved models
        }

        $pageType = $this->getPageTypeConfig();
        if ($pageType && $this->isTranslatableAttribute($key, $pageType)) {
            return $this->setTranslatableAttribute($key, $value);
        }

        return $this->setNonTranslatableAttribute($key, $value);
    }

    /**
     * Get page type configuration
     */
    public function getPageTypeConfig()
    {
        // If we have a loaded page relationship, use it
        if ($this->relationLoaded('page')) {
            $page = $this->getRelation('page');
            if ($page) {
                return PageTypeService::getPageTypeConfig($page->type_id);
            }
        }
        
        // If we have a page_id but no loaded relationship, load it directly from database
        if ($this->page_id) {
            try {
                $page = Page::find($this->page_id);
                if ($page) {
                    return PageTypeService::getPageTypeConfig($page->type_id);
                }
            } catch (\Exception $e) {
                // If there's any issue loading the page, return null
                return null;
            }
        }

        return null;
    }

    /**
     * Check if attribute is translatable
     */
    private function isTranslatableAttribute($key, $pageType)
    {
        return isset($pageType['post_attributes']['translatable'][$key]);
    }

    /**
     * Get translatable attribute value
     */
    private function getTranslatableAttribute($key)
    {
        $locale = app()->getLocale();
        $attribute = $this->attributes()
            ->where('attribute_key', $key)
            ->where('locale', $locale)
            ->first();

        return $attribute ? $attribute->attribute_value : null;
    }

    /**
     * Get non-translatable attribute value
     */
    private function getNonTranslatableAttribute($key)
    {
        $attribute = $this->attributes()
            ->where('attribute_key', $key)
            ->whereNull('locale')
            ->first();

        return $attribute ? $attribute->attribute_value : null;
    }

    /**
     * Set translatable attribute value
     */
    private function setTranslatableAttribute($key, $value)
    {
        // Don't save attributes if the post doesn't have an ID yet
        if (!$this->exists) {
            return;
        }
        
        $locale = app()->getLocale();
        $this->attributes()->updateOrCreate(
            [
                'attribute_key' => $key,
                'locale' => $locale
            ],
            [
                'attribute_value' => $value
            ]
        );
    }

    /**
     * Set non-translatable attribute value
     */
    private function setNonTranslatableAttribute($key, $value)
    {
        // Don't save attributes if the post doesn't have an ID yet
        if (!$this->exists) {
            return;
        }
        
        $this->attributes()->updateOrCreate(
            [
                'attribute_key' => $key,
                'locale' => null
            ],
            [
                'attribute_value' => $value
            ]
        );
    }

    /**
     * Get all attributes for a specific locale
     */
    public function getAttributesForLocale($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $pageType = $this->getPageTypeConfig();
        
        if (!$pageType) {
            return [];
        }

        $attributes = [];
        
        // Get translatable attributes
        if (isset($pageType['post_attributes']['translatable'])) {
            foreach ($pageType['post_attributes']['translatable'] as $key => $config) {
                $attributes[$key] = $this->getTranslatableAttribute($key);
            }
        }
        
        // Get non-translatable attributes
        if (isset($pageType['post_attributes']['non_translatable'])) {
            foreach ($pageType['post_attributes']['non_translatable'] as $key => $config) {
                $attributes[$key] = $this->getNonTranslatableAttribute($key);
            }
        }
        
        return $attributes;
    }

    /**
     * Get a single attribute for a specific locale
     */
    public function getAttributeForLocale($key, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $pageType = $this->getPageTypeConfig();
        
        if (!$pageType) {
            return null;
        }

        // Check if it's a translatable attribute
        if (isset($pageType['post_attributes']['translatable'][$key])) {
            $attribute = $this->attributes()
                ->where('attribute_key', $key)
                ->where('locale', $locale)
                ->first();
            return $attribute ? $attribute->attribute_value : null;
        }
        
        // Check if it's a non-translatable attribute
        if (isset($pageType['post_attributes']['non_translatable'][$key])) {
            $attribute = $this->attributes()
                ->where('attribute_key', $key)
                ->whereNull('locale')
                ->first();
            return $attribute ? $attribute->attribute_value : null;
        }
        
        return null;
    }

    /**
     * Scope for active posts
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope for published posts
     */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    /**
     * Scope for ordered posts
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }
}
