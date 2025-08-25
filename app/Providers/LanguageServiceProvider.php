<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Only run if the languages table exists
        try {
            if (Schema::hasTable('languages')) {
                // Cache the active languages for better performance
                $languages = Cache::rememberForever('active_languages', function () {
                    return Language::where('is_active', true)
                        ->orderBy('sort_order')
                        ->get();
                });

                // Share languages with all views
                View::share('availableLocales', $languages->pluck('code')->toArray());
                
                // Set the application locale if not already set
                if (!app()->runningInConsole()) {
                    $locale = session('locale', function () use ($languages) {
                        $default = $languages->where('is_default', true)->first();
                        return $default ? $default->code : config('app.locale');
                    });
                    
                    app()->setLocale($locale);
                }
            }
        } catch (\Exception $e) {
            // Handle the case where the database is not available yet
            // This is important for installation and migration processes
            if (!app()->runningInConsole()) {
                app()->setLocale(config('app.locale'));
            }
        }
    }
}
