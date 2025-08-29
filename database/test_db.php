<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Test database connection
    DB::connection()->getPdo();
    
    // Get table structure
    $columns = Schema::getColumnListing('web_users');
    
    echo "Database connection successful!\n";
    echo "Columns in web_users table:\n";
    print_r($columns);
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
