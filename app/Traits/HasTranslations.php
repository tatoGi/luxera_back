<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait for models that have translations
 */
trait HasTranslations
{
    /**
     * Boot the trait
     */
    protected static function bootHasTranslations()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                return;
            }
            $model->translations()->delete();
        });
    }

    /**
     * Get all translations for the model
     */
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Get a translation for a specific language
     */
    public function getTranslation(string $key, string $locale = null, bool $useFallback = true)
    {
        $locale = $locale ?: app()->getLocale();
        
        $translation = $this->translations()
            ->where('key', $key)
            ->where('language_code', $locale)
            ->first();

        if (!$translation && $useFallback) {
            $translation = $this->translations()
                ->where('key', $key)
                ->where('language_code', config('app.fallback_locale'))
                ->first();
        }

        return $translation ? $translation->value : null;
    }

    /**
     * Set a translation for a specific language
     */
    public function setTranslation(string $key, string $locale, string $value): self
    {
        $this->translations()->updateOrCreate(
            [
                'key' => $key,
                'language_code' => $locale,
            ],
            [
                'value' => $value,
            ]
        );

        return $this;
    }

    /**
     * Get all translations for a specific locale
     */
    public function getTranslationsForLocale(string $locale, bool $useFallback = true): array
    {
        $translations = [];
        
        $results = $this->translations()
            ->where('language_code', $locale)
            ->get();
            
        if ($results->isEmpty() && $useFallback) {
            $results = $this->translations()
                ->where('language_code', config('app.fallback_locale'))
                ->get();
        }
        
        foreach ($results as $translation) {
            $translations[$translation->key] = $translation->value;
        }
        
        return $translations;
    }

    /**
     * Get all translations for all locales
     */
    public function getAllTranslations(): array
    {
        $translations = [];
        
        $results = $this->translations()->get();
        
        foreach ($results as $translation) {
            if (!isset($translations[$translation->language_code])) {
                $translations[$translation->language_code] = [];
            }
            $translations[$translation->language_code][$translation->key] = $translation->value;
        }
        
        return $translations;
    }
}
