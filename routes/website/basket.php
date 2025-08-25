<?php

use App\Http\Controllers\Website\wishlistController;
use Illuminate\Support\Facades\Route;

Route::post('/add-to-wishlist', [wishlistController::class, 'addToWishlist'])->name('add.to.wishlist');
Route::post('/wishlist', [wishlistController::class, 'wishlist'])->name('wishlist');
Route::post('/remove-from-wishlist', [wishlistController::class, 'removeFromWishlist'])->name('removeFromWishlist');
Route::get('/show-cart', [wishlistController::class, 'fetchCartData'])->name('fetch-cart');
Route::post('/add-to-cart', [wishlistController::class, 'addToCart'])->name('add.to.cart');
Route::post('/remove-from-cart/{product}', [wishlistController::class, 'removeFromCart'])->name('remove-from-cart');

