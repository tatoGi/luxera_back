<?php

use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Website\FrontendController;
use App\Http\Controllers\Website\SearchController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Public website routes
Route::get('/', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/homepageData', [FrontendController::class, 'homepageData'])->name('homepage');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::post('/contact-submit', [FrontendController::class, 'submitContactForm'])->name('contact.submit');
Route::get('/sitemap', [SitemapController::class, 'generate']);
Route::get('/pro/{url}', [FrontendController::class, 'show'])->name('single_product');
Route::post('/subscribe', [FrontendController::class, 'subscribe'])->name('subscribe');
Route::get('/navigation', [FrontendController::class, 'navigation'])->name('website.navigation');
Route::get('/pages', [FrontendController::class, 'pages']);
Route::get('/pages-with-posts', [FrontendController::class, 'pagesWithPaginatedPosts'])->name('pages.with.posts');
Route::get('/categories', [FrontendController::class, 'categories']);
Route::post('/locale/sync', [FrontendController::class, 'localeSync'])->name('locale.sync');
Route::get('/languages', [FrontendController::class, 'languages'])->name('api.languages');
Route::post('/auth/send-welcome-email', [FrontendController::class, 'sendWelcomeEmail'])
    ->middleware('throttle:5,1')
    ->name('auth.email.welcome');

// Web user auth endpoints (public API)
Route::post('/login', [\App\Http\Controllers\Website\AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [\App\Http\Controllers\Website\AuthController::class, 'logout'])->name('auth.logout');

// API endpoints
Route::get('/blog-posts', [FrontendController::class, 'latestBlogPosts'])->name('api.blog.latest');
Route::get('/products', [FrontendController::class, 'products'])->name('api.products.list');
Route::get('/products/{id}', [FrontendController::class, 'productShow'])->name('api.products.show');

// Utility routes
Route::get('/clear-optimization', function () {
    Artisan::call('optimize:clear');
    return 'Optimization cache cleared!';
});
Route::get('/navigation', [FrontendController::class, 'navigation']);
// Keep catch-all route at the end
Route::get('/{slug}', [FrontendController::class, 'index'])->where('slug', '.*');

// Basket/wishlist/cart routes
require __DIR__.'/basket.php';
