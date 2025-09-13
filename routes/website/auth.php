<?php

use App\Http\Controllers\Website\Auth\GoogleController;
use App\Http\Controllers\Website\Auth\RegisterController;
use App\Http\Controllers\Website\AuthController;
use App\Http\Controllers\Website\ProfileController;
use Illuminate\Support\Facades\Route;

// Google OAuth routes
Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/auth/google/refresh-token', [GoogleController::class, 'refreshToken'])->name('auth.google.refresh');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('web.logout');

Route::post('/register', [RegisterController::class, 'register']);

// Protect profile routes with auth:webuser to ensure an authenticated WebUser instance
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user_profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// JSON profile routes for SPA usage
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [ProfileController::class, 'me'])->name('profile.me');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update.json');
    Route::post('/profile/retailer-request', [ProfileController::class, 'requestRetailer'])->name('profile.retailer.request');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
});

