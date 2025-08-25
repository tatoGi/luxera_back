<?php

namespace App\View\Components;

use App\Models\Language;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

class LanguageSwitcher extends Component
{
    public $availableLocales;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Get available locales from config or database
        $this->availableLocales = config('app.available_locales', []);
        
        // If no locales in config, try to get from database
        if (empty($this->availableLocales) && class_exists('App\Models\Language')) {
            try {
                $this->availableLocales = Cache::rememberForever('active_locales', function () {
                    return Language::getActive()
                        ->pluck('native', 'code')
                        ->toArray();
                });
            } catch (\Exception $e) {
                // Fallback to default locales if database is not available
                $this->availableLocales = [
                    'en' => 'English',
                    'ka' => 'ქართული',
                ];
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.language-switcher', [
            'availableLocales' => $this->availableLocales,
        ]);
    }
}
