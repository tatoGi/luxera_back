<?php

use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
Route::post('settings/edit', [SettingController::class, 'update'])->name('settings.update');
Route::post('settings/upload-file', [SettingController::class, 'uploadFile'])->name('settings.upload-file');
Route::post('settings/delete-file', [SettingController::class, 'deleteFile'])->name('settings.delete-file');