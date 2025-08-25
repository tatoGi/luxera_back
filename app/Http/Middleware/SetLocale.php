<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Language;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the first segment as locale
        $locale = $request->segment(1);
        
        // Get available locales from config
        $availableLocales = config('app.locales', []);
        
        // If the first segment is not a valid locale
        if (!in_array($locale, $availableLocales)) {
            // Try to get the default language from the database
            $defaultLanguage = cache()->rememberForever('default_language', function () {
                return \App\Models\Language::where('is_default', true)->first();
            });
            
            // If we have a default language, use it, otherwise use the fallback locale
            $fallback = $defaultLanguage ? $defaultLanguage->code : config('app.fallback_locale');
            
            // Get the current path without the locale
            $path = $request->path();
            
            // If the path is the root, just redirect to the default locale
            if ($path === '/') {
                return redirect()->to($fallback);
            }
            
            // Otherwise, prepend the default locale to the current path
            return redirect()->to("/{$fallback}/{$path}");
        }
        
        // Set the application locale
        app()->setLocale($locale);
        
        // Store the locale in the session
        session(['locale' => $locale]);
        
        // If this is a request to set the default language, update the session
        if ($request->has('set_default') && $request->isMethod('put')) {
            session(['locale' => $locale]);
        }
        
        // Remove the locale parameter from the route parameters
        $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
