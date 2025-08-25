<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing homepage post configuration...\n";

// Test the configuration
$config = include 'config/pageTypes/home.php';
echo "Section types available: " . implode(', ', array_keys($config['section_types'])) . "\n";

// Test PageTypeService
$translatableAttrs = \App\Services\PageTypeService::getTranslatableAttributes(1);
echo "Total translatable fields: " . count($translatableAttrs) . "\n";

$joinFields = array_filter($translatableAttrs, function($attr) {
    return isset($attr['show_for_types']) && in_array('join_us', $attr['show_for_types']);
});
echo "Join Us fields: " . count($joinFields) . "\n";

$rentalFields = array_filter($translatableAttrs, function($attr) {
    return isset($attr['show_for_types']) && in_array('rental_steps', $attr['show_for_types']);
});
echo "Rental Steps fields: " . count($rentalFields) . "\n";

if (count($rentalFields) > 0) {
    echo "First rental field: " . array_keys($rentalFields)[0] . "\n";
    $firstField = array_values($rentalFields)[0];
    echo "Show for types: " . json_encode($firstField['show_for_types']) . "\n";
}

// Test non-translatable
$nonTranslatableAttrs = \App\Services\PageTypeService::getNonTranslatableAttributes(1);
echo "Total non-translatable fields: " . count($nonTranslatableAttrs) . "\n";

$rentalNonTransFields = array_filter($nonTranslatableAttrs, function($attr) {
    return isset($attr['show_for_types']) && in_array('rental_steps', $attr['show_for_types']);
});
echo "Rental Steps non-translatable fields: " . count($rentalNonTransFields) . "\n";
