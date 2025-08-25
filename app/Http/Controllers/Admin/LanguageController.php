<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage_languages');
    }
    
    /**
     * Sync available locales from config with the database
     */
    protected function syncLocales()
    {
        $locales = config('app.locales', []);
        $existingLanguages = Language::whereIn('code', $locales)->pluck('code')->toArray();
        
        foreach ($locales as $locale) {
            if (!in_array($locale, $existingLanguages)) {
                $languageData = [
                    'code' => $locale,
                    'name' => locale_get_display_name($locale, 'en'),
                    'native_name' => locale_get_display_name($locale, $locale),
                    'is_active' => true,
                    'is_default' => $locale === config('app.locale'),
                    'sort_order' => Language::count() + 1,
                ];
                
                Language::create($languageData);
            }
        }
        
        // Ensure there's always a default language
        $defaultLanguage = Language::where('is_default', true)->first();
        if (!$defaultLanguage) {
            $firstLanguage = Language::first();
            if ($firstLanguage) {
                $firstLanguage->update(['is_default' => true]);
            }
        }
    }

    public function index()
    {
        // Sync available locales with the database
        $this->syncLocales();
        
        $languages = Language::orderBy('sort_order')->get();
        $availableLocales = collect(config('app.available_locales', []))->mapWithKeys(function ($locale) {
            return [$locale => [
                'name' => locale_get_display_name($locale, 'en'),
                'native_name' => locale_get_display_name($locale, $locale)
            ]];
        });
        
        return view('admin.languages.index', compact('languages', 'availableLocales'));
    }

    public function create()
    {
        $availableLocales = collect(config('app.available_locales', []))->filter(function ($locale) {
            return !Language::where('code', $locale)->exists();
        })->mapWithKeys(function ($locale) {
            return [$locale => locale_get_display_name($locale, 'en') . ' (' . $locale . ')'];
        });
        
        return view('admin.languages.create', compact('availableLocales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:languages,code',
            'name' => 'required|string|max:100',
            'native_name' => 'required|string|max:100',
            'is_default' => 'nullable|in:on',
            'is_active' => 'nullable|in:on',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Convert checkbox values to boolean
        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');
       
        // If this is set as default, unset other defaults
        if ($validated['is_default']) {
            Language::where('is_default', true)->update(['is_default' => false]);
        } else {
            // If no default exists, make this one default
            $defaultExists = Language::where('is_default', true)->exists();
            if (!$defaultExists) {
                $validated['is_default'] = true;
            }
        }

        try {
            $language = Language::create($validated);
            $this->updateAppLocales($language->code);
            $this->copyDefaultTranslations($language->code);
            $this->clearCache();
        } catch (\Exception $e) {
            // If something goes wrong, clean up
            if (isset($language) && $language->exists) {
                $language->delete();
            }
            throw $e;
        }

        return redirect()->route('admin.languages.index', ['locale' => app()->getLocale()])
            ->with('success', __('Language created successfully.'));
    }

    public function edit(Language $language)
    {
        // Get groups from both database and filesystem translation files
        $dbGroups = Translation::distinct()->pluck('group')->toArray();
        
        // Get groups from filesystem translation files
        $filesystemGroups = [];
        $langPath = resource_path('lang/en'); // Use default language path
        if (is_dir($langPath)) {
            $files = glob($langPath . '/*.php');
            foreach ($files as $file) {
                $filesystemGroups[] = basename($file, '.php');
            }
        }
        
        // Merge and get unique groups
        $groups = collect(array_merge($dbGroups, $filesystemGroups))->unique()->sort()->values();
        
        $translations = $language->translations()
            ->orderBy('group')
            ->orderBy('key')
            ->get()
            ->groupBy('group');

        return view('admin.languages.edit', compact('language', 'groups', 'translations'));
    }

    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'native_name' => 'required|string|max:100',
            'is_default' => 'nullable|in:on',
            'is_active' => 'nullable|in:on',
            'sort_order' => 'nullable|integer|min:0',
        ]);
       
        // Convert checkbox values to boolean
        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');

        // If this is set as default, unset other defaults
        if ($validated['is_default']) {
            Language::where('is_default', true)
                ->where('id', '!=', $language->id)
                ->update(['is_default' => false]);
        }

        $oldCode = $language->code;
        $wasDefault = $language->is_default;
        
        // If this language is being set as default, unset other defaults
        if ($validated['is_default'] && !$wasDefault) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }
        
        $language->update($validated);
        
        // If language code was changed, update it in the config
        if ($oldCode !== $language->code) {
            $this->updateAppLocales($language->code, $oldCode);
        } else {
            $this->updateAppLocales($language->code);
        }
        
        // Clear all relevant caches
        $this->clearCache();
        cache()->forget('default_language');
        
        // If this language is now the default, redirect to the new default locale
        if ($validated['is_default'] && !$wasDefault) {
            return redirect()->to("/{$language->code}/admin/languages")
                ->with('success', __('Language set as default successfully.'));
        }
        
        // If the language code changed, redirect to the new URL
        if ($oldCode !== $language->code) {
            return redirect()->route('admin.languages.edit', [
                'locale' => $language->code,
                'language' => $language->id
            ])->with('success', __('Language updated successfully.'));
        }
        
        return redirect()->route('admin.languages.index', ['locale' => app()->getLocale()])
            ->with('success', __('Language updated successfully.'));
    }

    public function destroy(Language $language)
    {
        if ($language->is_default) {
            return back()->with('error', __('Cannot delete default language.'));
        }
        
        try {
            // Store the language code before deletion
            $languageCode = $language->code;
            
            // Delete the language (cascade will handle translations)
            $deleted = $language->delete();
            
            if (!$deleted) {
                return back()->with('error', __('Failed to delete language. Please try again.'));
            }
            
            // Remove the language code from config/app.php locales array
            $this->updateAppLocales('', $languageCode);
            
            // Clear all language-related caches
            $this->clearCache();

            return redirect()->route('admin.languages.index', ['locale' => app()->getLocale()])
                ->with('success', __('Language deleted successfully.'));
                
        } catch (\Exception $e) {
            Log::error('Language deletion failed: ' . $e->getMessage());
            return back()->with('error', __('Failed to delete language: ') . $e->getMessage());
        }
    }

    public function updateTranslations(Request $request, Language $language)
    {
        $request->validate([
            'translations' => 'required|array',
            'translations.*.key' => 'required|string',
            'translations.*.group' => 'required|string',
            'translations.*.value' => 'required|string',
        ]);

        foreach ($request->translations as $translation) {
            $language->translations()->updateOrCreate(
                [
                    'key' => $translation['key'],
                    'group' => $translation['group'],
                ],
                ['value' => $translation['value']]
            );
        }

        return response()->json(['success' => true]);
    }

    public function addTranslation(Request $request, Language $language)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'group' => 'required|string|max:100',
            'value' => 'required|string',
        ]);

        $translation = $language->translations()->create($validated);

        return response()->json([
            'success' => true,
            'translation' => $translation
        ]);
    }

    public function deleteTranslation(Translation $translation)
    {
        $translation->delete();
        return response()->json(['success' => true]);
    }

    public function sync()
    {
        $locales = config('app.available_locales', []);
        $added = [];
        
        foreach ($locales as $locale) {
            $exists = Language::where('code', $locale)->exists();
            if (!$exists) {
                $languageData = [
                    'code' => $locale,
                    'name' => locale_get_display_name($locale, 'en'),
                    'native_name' => locale_get_display_name($locale, $locale),
                    'is_active' => true,
                    'is_default' => $locale === config('app.locale'),
                    'sort_order' => Language::count() + 1,
                ];
                
                Language::create($languageData);
                $added[] = $languageData['name'] . ' (' . $locale . ')';
            }
        }
        
        // Ensure there's a default language
        $defaultLanguage = Language::where('is_default', true)->first();
        if (!$defaultLanguage) {
            $firstLanguage = Language::first();
            if ($firstLanguage) {
                $firstLanguage->update(['is_default' => true]);
                $added[] = $firstLanguage->name . ' (' . $firstLanguage->code . ') set as default';
            }
        }
        
        $this->clearCache();
        
        if (count($added) > 0) {
            return redirect()->route('admin.languages.index')
                ->with('success', 'Added default languages: ' . implode(', ', $added));
        }
        
        return redirect()->route('admin.languages.index')
            ->with('info', 'All default languages are already added.');
    }

    public function clearCache()
    {
        Cache::forget('active_languages');
        Cache::forget('languages.active');
        Cache::forget('languages.default');
        Cache::forget('default_language');
    }
    
    /**
     * Update the locales array in the config file
     */
    /**
     * Update the locales array in the config file
     */
    protected function updateAppLocales($newCode, $oldCode = null)
    {
        $configPath = config_path('app.php');
        $config = file_get_contents($configPath);
        
        // Check if locales array exists
        if (!preg_match("/'locales'\s*=>\s*\[/", $config)) {
            // Add locales array if it doesn't exist
            $config = preg_replace(
                "/'timezone'\s*=>\s*'[^']*',/",
                "'timezone' => 'UTC',\n        \n        /*\n        |--------------------------------------------------------------------------\n        | Application Available Locales\n        |--------------------------------------------------------------------------\n        |\n        | This array contains all available locales for the application.\n        |\n        */\n        \n        'locales' => ['ka', 'en'],",
                $config
            );
        }
        
        // Get current locales
        preg_match("/'locales'\s*=>\s*\[(.*?)\],/s", $config, $matches);
        $locales = [];
        if (isset($matches[1])) {
            preg_match_all("/'([^']+)'/", $matches[1], $localesMatches);
            $locales = $localesMatches[1];
        }
        
        // Remove old code if it exists and is different from new code
        if ($oldCode !== null && $oldCode !== $newCode && in_array($oldCode, $locales)) {
            $locales = array_diff($locales, [$oldCode]);
        }
        
        // Add new language code if it doesn't exist and is not empty
        if (!empty($newCode) && !in_array($newCode, $locales)) {
            $locales[] = $newCode;
        }
        
        // Sort locales alphabetically for consistency
        sort($locales);
        
        // Update the config file
        $localesString = "['" . implode("', '", $locales) . "']";
        $config = preg_replace(
            "/'locales'\s*=>\s*\[.*?\],/s",
            "'locales' => $localesString,",
            $config
        );
        
        // Write back to config file
        file_put_contents($configPath, $config);
    }
    
    /**
     * Copy default translation files to a new language directory
     */
    protected function copyDefaultTranslations($languageCode)
    {
        $defaultLang = 'en'; // Default language to copy from
        $sourcePath = resource_path("lang/{$defaultLang}");
        $targetPath = resource_path("lang/{$languageCode}");
        
        // Create target directory if it doesn't exist
        if (!file_exists($targetPath)) {
            if (!mkdir($targetPath, 0755, true)) {
                throw new \RuntimeException("Failed to create language directory: {$targetPath}");
            }
        }
        
        // Copy all PHP files from default language directory
        $files = glob($sourcePath . '/*.php');
        foreach ($files as $file) {
            $targetFile = $targetPath . '/' . basename($file);
            if (!copy($file, $targetFile)) {
                throw new \RuntimeException("Failed to copy translation file: {$file} to {$targetFile}");
            }
        }
        
        // Also copy JSON translation file if it exists
        $jsonFile = resource_path("lang/{$defaultLang}.json");
        if (file_exists($jsonFile)) {
            $targetJson = resource_path("lang/{$languageCode}.json");
            if (!copy($jsonFile, $targetJson)) {
                throw new \RuntimeException("Failed to copy JSON translation file to {$targetJson}");
            }
        }
    }
    
    /**
     * Export database translations to filesystem files
     */
    public function exportTranslations(Language $language)
    {
        $translations = $language->translations()->get()->groupBy('group');
        
        foreach ($translations as $group => $groupTranslations) {
            $langPath = resource_path("lang/{$language->code}");
            
            // Create directory if it doesn't exist
            if (!file_exists($langPath)) {
                mkdir($langPath, 0755, true);
            }
            
            $filePath = "{$langPath}/{$group}.php";
            
            // Load existing translations from file
            $existingTranslations = [];
            if (file_exists($filePath)) {
                $existingTranslations = include $filePath;
            }
            
            // Merge database translations with existing file translations
            foreach ($groupTranslations as $translation) {
                $existingTranslations[$translation->key] = $translation->value;
            }
            
            // Write the updated translations back to the file
            $content = "<?php\n\nreturn " . var_export($existingTranslations, true) . ";\n";
            file_put_contents($filePath, $content);
        }
        
        return response()->json(['success' => true, 'message' => 'Translations exported successfully']);
    }
}
