<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Website\Auth\RegisterController as WebsiteRegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Registration route (no CSRF required)
Route::post('/send-registration-email', [App\Http\Controllers\Api\EmailController::class, 'sendRegistrationEmail'])->name('api.email.registration');
Route::post('/send-welcome-email', [App\Http\Controllers\Api\EmailController::class, 'sendWelcomeEmail'])->name('api.email.welcome');

Route::post('/register', [WebsiteRegisterController::class, 'register']);


