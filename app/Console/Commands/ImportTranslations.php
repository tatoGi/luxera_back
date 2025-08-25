<?php

namespace App\Console\Commands;

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class ImportTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:import {--force : Overwrite existing translations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import translation files into the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $force = $this->option('force');
        
        // Get all language directories
        $langPath = resource_path('lang');
        if (!File::isDirectory($langPath)) {
            $this->error("Language directory not found: {$langPath}");
            return 1;
        }

        $locales = array_map('basename', File::directories($langPath));
        
        if (empty($locales)) {
            $this->warn('No language directories found.');
            return 0;
        }

        $this->info('Found locales: ' . implode(', ', $locales));
        
        // Create or update languages in the database
        foreach ($locales as $locale) {
            $language = Language::firstOrCreate(
                ['code' => $locale],
                [
                    'name' => $this->getLanguageName($locale),
                    'native_name' => $this->getLanguageName($locale, true),
                    'is_active' => true,
                    'sort_order' => $locale === 'en' ? 0 : 1,
                ]
            );

            if ($language->wasRecentlyCreated) {
                $this->info("Created language: {$locale}");
            }
        }

        // Import translations for each language
        foreach ($locales as $locale) {
            $this->importTranslationsForLocale($locale, $force);
        }

        // Clear the cache
        Cache::forget('active_locales');
        
        $this->info('Translations imported successfully!');
        return 0;
    }

    /**
     * Import translations for a specific locale
     */
    protected function importTranslationsForLocale(string $locale, bool $force = false)
    {
        $langPath = resource_path("lang/{$locale}");
        $files = File::allFiles($langPath);
        
        if (empty($files)) {
            $this->warn("No translation files found for locale: {$locale}");
            return;
        }

        $language = Language::where('code', $locale)->first();
        if (!$language) {
            $this->error("Language not found for locale: {$locale}");
            return;
        }

        $bar = $this->output->createProgressBar(count($files));
        $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% %message%");
        $bar->setMessage('Starting import...');
        $bar->start();

        $imported = 0;
        $skipped = 0;
        $updated = 0;

        foreach ($files as $file) {
            $group = $file->getBasename('.php');
            $translations = include $file->getPathname();
            
            if (!is_array($translations)) {
                $bar->advance();
                $bar->setMessage("Skipping invalid file: {$file->getFilename()}");
                $skipped++;
                continue;
            }

            foreach ($this->flattenTranslations($translations) as $key => $value) {
                $existing = Translation::where([
                    'language_id' => $language->id,
                    'group' => $group,
                    'key' => $key,
                ])->first();

                if ($existing) {
                    if ($force) {
                        $existing->update(['value' => $value]);
                        $updated++;
                    } else {
                        $skipped++;
                    }
                } else {
                    Translation::create([
                        'language_id' => $language->id,
                        'group' => $group,
                        'key' => $key,
                        'value' => $value,
                    ]);
                    $imported++;
                }
            }

            $bar->advance();
            $bar->setMessage("Processed {$file->getFilename()}");
        }

        $bar->finish();
        $this->line('');
        $this->info("Locale {$locale}: Imported {$imported}, Updated {$updated}, Skipped {$skipped} translations");
    }

    /**
     * Flatten a multi-dimensional associative array with dots
     */
    protected function flattenTranslations(array $translations, string $prefix = ''): array
    {
        $result = [];
        
        foreach ($translations as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;
            
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenTranslations($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        
        return $result;
    }

    /**
     * Get the language name for a given locale code
     */
    protected function getLanguageName(string $locale, bool $native = false): string
    {
        $languages = [
            'en' => ['name' => 'English', 'native' => 'English'],
            'ka' => ['name' => 'Georgian', 'native' => 'ქართული'],
            // Add more languages as needed
        ];

        return $languages[$locale][$native ? 'native' : 'name'] ?? ucfirst($locale);
    }
}
