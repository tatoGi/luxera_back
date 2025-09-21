<?php

use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Website\FrontendController;
use App\Http\Controllers\Website\ProfileController;
use App\Http\Controllers\Website\SearchController;
use App\Http\Controllers\Website\wishlistController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

// Test routes
require __DIR__.'/test.php';
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

require __DIR__.'/website/auth.php';
// Email API routes (outside locale group for direct access)

    // Admin routes with auth middleware
    Route::middleware(['auth'])->group(function () {
        Route::prefix('admin')->group(function () {
            require __DIR__.'/admin/admin.php';
            require __DIR__.'/admin/products.php';
            require __DIR__.'/admin/page.php';
            require __DIR__.'/admin/settings.php';
            require __DIR__.'/admin/languages.php'; // Include language management routes
        });
    });

    // Website routes extracted to a dedicated file
    require __DIR__.'/website/public.php';

// Set the locale for the application (without locale prefix)
Route::get('/change-locale/{lang}', function ($lang) {
    if (in_array($lang, array_keys(config('app.locales')))) {
        session(['locale' => $lang]);
        app()->setLocale($lang);

        // Get the redirect path and clean it from any locale prefixes
        $redirect = request('redirect', '/');
        $redirect = ltrim(preg_replace('#^[a-z]{2}(?:-[A-Z]{2})?/#', '', $redirect), '/');
        $redirect = $lang === 'en' ? $redirect : $lang . '/' . $redirect;

        return redirect()->to($redirect);
    }
    return back();
})->name('set.locale')->withoutMiddleware(['locale']);

// Catch-all and basket routes are included via website/public.php



// Example of running multiple database queries sequentially (without Boost)


