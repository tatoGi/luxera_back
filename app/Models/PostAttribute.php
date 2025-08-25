<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'attribute_key',
        'attribute_value',
        'locale'
    ];

    /**
     * Get the post that owns this attribute
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Scope for translatable attributes
     */
    public function scopeTranslatable($query)
    {
        return $query->whereNotNull('locale');
    }

    /**
     * Scope for non-translatable attributes
     */
    public function scopeNonTranslatable($query)
    {
        return $query->whereNull('locale');
    }

    /**
     * Scope for specific locale
     */
    public function scopeForLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope for specific attribute key
     */
    public function scopeForKey($query, $key)
    {
        return $query->where('attribute_key', $key);
    }
}
