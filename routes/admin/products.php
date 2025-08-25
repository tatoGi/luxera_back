<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PageManagementController;
use Illuminate\Support\Facades\Route;

// Regular standalone product routes
Route::resource('/products', ProductController::class)->parameters([
    'products' => 'product'
]);

// Product image management
Route::delete('/products/delete/image/{image_id}', [ProductController::class, 'deleteImage'])->name('products.images.delete');
Route::get('/products/cleanup-images', [ProductController::class, 'cleanupMissingImages'])->name('products.cleanup.images');

// Product page management routes
Route::get('/products/{product}/pages', [ProductController::class, 'managePages'])->name('admin.products.pages.manage');
Route::post('/products/{product}/pages/attach', [ProductController::class, 'attachPage'])->name('admin.products.pages.attach');
Route::delete('/products/{product}/pages/{page}/detach', [ProductController::class, 'detachPage'])->name('admin.products.pages.detach');

// Page-specific product routes (nested under pages)
Route::prefix('pages/{page}')->name('admin.pages.')->group(function () {
    Route::get('/products', [ProductController::class, 'indexForPage'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'createForPage'])->name('products.create');
    Route::post('/products', [ProductController::class, 'storeForPage'])->name('products.store');
    Route::post('/products/attach', [ProductController::class, 'attachToPage'])->name('products.attach');
    Route::delete('/products/{product}/detach', [ProductController::class, 'detachFromPage'])->name('products.detach');
});

// Page management routes (legacy - these should be removed as they duplicate routes in page.php)
// Route::get('/page-management/manage/{page}', [PageManagementController::class, 'manage'])->name('admin.pages.management.manage');
// Route::get('/page-management/index/{page}', [PageManagementController::class, 'index'])->name('admin.pages.management.index');