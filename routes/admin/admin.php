<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\WebUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::resource('/categories', CategoryController::class);
Route::get('/contact', [ContactController::class, 'index'])->name('admin.contact');
Route::get('/subscribers', [ContactController::class, 'subscribers']);
Route::get('/languages', [LanguageController::class, 'index'])->name('languages.index');
Route::post('/languages/update', [LanguageController::class, 'update'])->name('languages.update');
Route::resource('/banners', BannerController::class)->parameters([
    'banners' => 'banner',
    'page_id' => 'page'
]);
Route::get('/banners/create/{page_id?}', [BannerController::class, 'create'])->name('banners.create.with.page');
Route::delete('/category/icon/{id}', [CategoryController::class, 'deleteIcon'])->name('category.icon.delete');
Route::get('/webusers', [WebUserController::class, 'index']);
Route::delete('/banners/delete/image/{image_id}', [BannerController::class, 'deleteImage'])->name('banners.images.delete');

// Image upload route for TinyMCE editor
Route::post('/upload-image', [PostController::class, 'uploadImage'])->name('admin.upload.image');

// Post management routes (nested under pages)
Route::prefix('pages/{page}')->name('admin.pages.')->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});
