<?php

use App\Http\Controllers\Admin\LanguageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:manage_languages'])->group(function () {
   Route::get('languages', [LanguageController::class, 'index'])
        ->name('admin.languages.index');
    Route::get('languages/create', [LanguageController::class, 'create'])
        ->name('admin.languages.create');
    Route::post('languages', [LanguageController::class, 'store'])
        ->name('admin.languages.store');
    Route::get('languages/{language}/edit', [LanguageController::class, 'edit'])
        ->name('admin.languages.edit');
    Route::put('languages/{language}', [LanguageController::class, 'update'])
        ->name('admin.languages.update');
    Route::delete('languages/{language}', [LanguageController::class, 'destroy'])
        ->name('admin.languages.destroy');
    Route::post('languages/sync', [LanguageController::class, 'sync'])
        ->name('admin.languages.sync');
    // Additional routes for translations
    Route::post('languages/{language}/translations', [LanguageController::class, 'updateTranslations'])
        ->name('admin.languages.update.translations');
        
    Route::delete('languages/{language}/translations/{translation}', [LanguageController::class, 'deleteTranslation'])
        ->name('admin.languages.delete.translation');
        
    Route::post('languages/{language}/add-translation', [LanguageController::class, 'addTranslation'])
        ->name('admin.languages.add.translation');
        
    Route::post('languages/{language}/export', [LanguageController::class, 'exportTranslations'])
        ->name('admin.languages.export');
});
