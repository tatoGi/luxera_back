<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class PageTypeService
{
    /**
     * Get all available page types
     */
    public static function getAllPageTypes()
    {
        $pageTypes = [];
        $configPath = config_path('pagetypes');
        
        if (!File::exists($configPath)) {
            return $pageTypes;
        }
        
        $files = File::files($configPath);
        
        foreach ($files as $file) {
            $filename = $file->getFilenameWithoutExtension();
            $config = include $file->getPathname();
            
            if ($config && is_array($config)) {
                $pageTypes[$config['type']] = $config;
            }
        }
        
        return $pageTypes;
    }
    
    /**
     * Get page type configuration by type ID
     */
    public static function getPageTypeConfig($typeId)
    {
        $configFiles = [
            1 => 'home',
            2 => 'blog', 
            3 => 'contact',
            4 => 'faq',
            5 => 'routes'
        ];
        
        $configFile = $configFiles[$typeId] ?? null;
        if (!$configFile) {
            return null;
        }
        
        // Load config file directly from the pagetypes directory
        $configPath = config_path("pagetypes/{$configFile}.php");
        if (file_exists($configPath)) {
            return include $configPath;
        }
        
        return null;
    }
    
    /**
     * Get page types that support posts
     */
    public static function getPageTypesWithPosts()
    {
        $allTypes = self::getAllPageTypes();
        
        return array_filter($allTypes, function($config) {
            return $config['has_posts'] ?? false;
        });
    }
    
    /**
     * Get post attributes for a specific page type
     */
    public static function getPostAttributes($typeId)
    {
        $config = self::getPageTypeConfig($typeId);
        
        if (!$config || !($config['has_posts'] ?? false)) {
            return [];
        }
        
        return $config['post_attributes'] ?? [];
    }
    
    /**
     * Get translatable attributes for a page type
     */
    public static function getTranslatableAttributes($typeId)
    {
        $config = self::getPageTypeConfig($typeId);
        
        // If using new section_types structure, convert to legacy format
        if (isset($config['section_types'])) {
            $translatable = [];
            
            foreach ($config['section_types'] as $sectionType => $sectionConfig) {
                foreach ($sectionConfig['translatable_fields'] as $fieldKey => $fieldConfig) {
                    $fieldConfig['show_for_types'] = [$sectionType];
                    $translatable[$fieldKey] = $fieldConfig;
                }
            }
            
            return $translatable;
        }
        
        // Fallback to legacy structure
        $attributes = self::getPostAttributes($typeId);
        return $attributes['translatable'] ?? [];
    }
    
    /**
     * Get non-translatable attributes for a page type
     */
    public static function getNonTranslatableAttributes($typeId)
    {
        $config = self::getPageTypeConfig($typeId);
        
        // If using new section_types structure, convert to legacy format
        if (isset($config['section_types'])) {
            $nonTranslatable = [];
            
            // Add section-specific fields
            foreach ($config['section_types'] as $sectionType => $sectionConfig) {
                foreach ($sectionConfig['non_translatable_fields'] as $fieldKey => $fieldConfig) {
                    $fieldConfig['show_for_types'] = [$sectionType];
                    $nonTranslatable[$fieldKey] = $fieldConfig;
                }
            }
            
            // Add common fields from legacy structure
            $attributes = self::getPostAttributes($typeId);
            if (isset($attributes['non_translatable'])) {
                foreach ($attributes['non_translatable'] as $fieldKey => $fieldConfig) {
                    $nonTranslatable[$fieldKey] = $fieldConfig;
                }
            }
            
            return $nonTranslatable;
        }
        
        // Fallback to legacy structure
        $attributes = self::getPostAttributes($typeId);
        return $attributes['non_translatable'] ?? [];
    }
    
    /**
     * Check if a page type supports posts
     */
    public static function supportsPost($typeId)
    {
        $config = self::getPageTypeConfig($typeId);
        return $config && ($config['has_posts'] ?? false);
    }
    
    /**
     * Get filtered validation rules for post attributes based on post type
     */
    public static function getFilteredValidationRules($typeId, $postType = null)
    {
        $translatableAttributes = self::getTranslatableAttributes($typeId);
        $nonTranslatableAttributes = self::getNonTranslatableAttributes($typeId);
        $rules = [];
        
        // Process translatable attributes
        foreach ($translatableAttributes as $key => $config) {
            // Skip fields that don't match the selected post type
            if ($postType && isset($config['show_for_types']) && !in_array($postType, $config['show_for_types'])) {
                continue;
            }
            
            foreach (config('app.locales') as $locale) {
                $fieldName = "{$locale}.{$key}";
                $fieldRules = [];
                
                if ($config['required'] ?? false) {
                    $fieldRules[] = 'required';
                } else {
                    $fieldRules[] = 'nullable';
                }
                
                // Add type-specific rules
                switch ($config['type']) {
                    case 'text':
                    case 'textarea':
                        $fieldRules[] = 'string';
                        $fieldRules[] = 'max:255';
                        break;
                    case 'editor':
                        $fieldRules[] = 'string';
                        break;
                    case 'email':
                        $fieldRules[] = 'email';
                        break;
                    case 'url':
                        $fieldRules[] = 'url';
                        break;
                    case 'number':
                        $fieldRules[] = 'numeric';
                        break;
                    case 'datetime-local':
                        $fieldRules[] = 'date';
                        break;
                }
                
                $rules[$fieldName] = implode('|', $fieldRules);
            }
        }
        
        // Process non-translatable attributes
        foreach ($nonTranslatableAttributes as $key => $config) {
            // Skip fields that don't match the selected post type
            if ($postType && isset($config['show_for_types']) && !in_array($postType, $config['show_for_types'])) {
                continue;
            }
            
            $fieldRules = [];
            
            if ($config['required'] ?? false) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }
            
            // Add type-specific rules
            switch ($config['type']) {
                case 'text':
                case 'textarea':
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:255';
                    break;
                case 'editor':
                    $fieldRules[] = 'string';
                    break;
                case 'image':
                    $fieldRules[] = 'image';
                    $fieldRules[] = 'mimes:jpeg,png,jpg,gif,svg,webp';
                    break;
                case 'email':
                    $fieldRules[] = 'email';
                    break;
                case 'url':
                    $fieldRules[] = 'url';
                    break;
                case 'number':
                    $fieldRules[] = 'numeric';
                    break;
                case 'datetime-local':
                    $fieldRules[] = 'date';
                    break;
                case 'select':
                    if (isset($config['options']) && is_array($config['options'])) {
                        $options = array_keys($config['options']);
                        $fieldRules[] = 'in:' . implode(',', $options);
                    }
                    break;
                case 'boolean':
                    $fieldRules[] = 'boolean';
                    break;
            }
            
            $rules[$key] = implode('|', $fieldRules);
        }
        
        return $rules;
    }

    /**
     * Get validation rules for post attributes
     */
    public static function getValidationRules($typeId)
    {
        $attributes = self::getPostAttributes($typeId);
        $rules = [];
        
        // Process translatable attributes
        if (isset($attributes['translatable'])) {
            foreach ($attributes['translatable'] as $key => $config) {
                foreach (config('app.locales') as $locale) {
                    $fieldName = "{$locale}.{$key}";
                    $fieldRules = [];
                    
                    if ($config['required'] ?? false) {
                        $fieldRules[] = 'required';
                    } else {
                        $fieldRules[] = 'nullable';
                    }
                    
                    // Add type-specific rules
                    switch ($config['type']) {
                        case 'text':
                        case 'textarea':
                            $fieldRules[] = 'string';
                            $fieldRules[] = 'max:255';
                            break;
                        case 'editor':
                            $fieldRules[] = 'string';
                            break;
                        case 'email':
                            $fieldRules[] = 'email';
                            break;
                        case 'url':
                            $fieldRules[] = 'url';
                            break;
                        case 'number':
                            $fieldRules[] = 'numeric';
                            break;
                        case 'datetime-local':
                            $fieldRules[] = 'date';
                            break;
                    }
                    
                    $rules[$fieldName] = implode('|', $fieldRules);
                }
            }
        }
        
        // Process non-translatable attributes
        if (isset($attributes['non_translatable'])) {
            foreach ($attributes['non_translatable'] as $key => $config) {
                $fieldRules = [];
                
                if ($config['required'] ?? false) {
                    $fieldRules[] = 'required';
                } else {
                    $fieldRules[] = 'nullable';
                }
                
                // Add type-specific rules
                switch ($config['type']) {
                    case 'text':
                    case 'textarea':
                        $fieldRules[] = 'string';
                        $fieldRules[] = 'max:255';
                        break;
                    case 'editor':
                        $fieldRules[] = 'string';
                        break;
                    case 'image':
                        $fieldRules[] = 'image';
                        $fieldRules[] = 'mimes:jpeg,png,jpg,gif,svg,webp';
                        break;
                    case 'email':
                        $fieldRules[] = 'email';
                        break;
                    case 'url':
                        $fieldRules[] = 'url';
                        break;
                    case 'number':
                        $fieldRules[] = 'numeric';
                        break;
                    case 'datetime-local':
                        $fieldRules[] = 'date';
                        break;
                    case 'select':
                        if (isset($config['options']) && is_array($config['options'])) {
                            $options = array_keys($config['options']);
                            $fieldRules[] = 'in:' . implode(',', $options);
                        }
                        break;
                    case 'boolean':
                        $fieldRules[] = 'boolean';
                        break;
                }
                
                $rules[$key] = implode('|', $fieldRules);
            }
        }
        
        return $rules;
    }
}
