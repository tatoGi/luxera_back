<?php

use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageOptionController;
use App\Http\Controllers\Admin\PageManagementController;
use Illuminate\Support\Facades\Route;

Route::resource('/pages', PageController::class);
Route::resource('/options', PageOptionController::class);
Route::post('/pages/arrange', [PageController::class, 'arrange']);

// Page Management Routes
Route::get('/pages/{page}/manage', [PageManagementController::class, 'manage'])->name('admin.pages.management.manage');
Route::post('/pages/{page}/banners/attach', [PageManagementController::class, 'attachBanner'])->name('admin.pages.banners.attach');
Route::delete('/pages/{page}/banners/{banner}/detach', [PageManagementController::class, 'detachBanner'])->name('admin.pages.banners.detach');
Route::post('/pages/{page}/products/attach', [PageManagementController::class, 'attachProduct'])->name('admin.pages.products.attach');
Route::delete('/pages/{page}/products/{product}/detach', [PageManagementController::class, 'detachProduct'])->name('admin.pages.products.detach');

