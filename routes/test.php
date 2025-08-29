<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/test-db', function() {
    try {
        // Test database connection
        DB::connection()->getPdo();
        
        // Get table structure
        $columns = Schema::getColumnListing('web_users');
        
        return response()->json([
            'status' => 'success',
            'columns' => $columns
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});
