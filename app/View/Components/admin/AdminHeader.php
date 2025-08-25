<?php

namespace App\View\Components\admin;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\View\Component;

class AdminHeader extends Component
{
    /**
     * The available locales.
     *
     * @var array
     */
    public $locales;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->locales = [];

        foreach (Config::get('app.locales') as $locale) {

            $currentUrl = request()->fullUrl();
            $currentLocale = app()->getLocale();
            
            // Replace the locale in the URL
            $this->locales[$locale] = preg_replace(
                "/(^|\/)" . preg_quote($currentLocale, '/') . "(\/|$)/i",
                '$1' . $locale . '$2',
                $currentUrl
            );
            
            // If no replacement was made, we need to add the locale
            if ($this->locales[$locale] === $currentUrl) {
                $parsedUrl = parse_url($currentUrl);
                $path = $parsedUrl['path'] ?? '/';
                $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
                $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';
                
                // Add locale to the beginning of the path
                $newPath = '/' . $locale . ($path === '/' ? '' : $path);
                $this->locales[$locale] = ($parsedUrl['scheme'] ?? 'http') . '://' . 
                                        ($parsedUrl['host'] ?? '') . 
                                        $newPath . 
                                        $query . 
                                        $fragment;
            }
        }

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.admin-header');
    }
}
