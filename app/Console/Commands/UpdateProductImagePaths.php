<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class UpdateProductImagePaths extends Command
{
    protected $signature = 'product:update-image-paths';
    protected $description = 'Update product image paths in the database to match the new format';

    public function handle()
    {
        $images = ProductImage::all();
        $count = 0;

        foreach ($images as $image) {
            $oldPath = $image->image_name;

            // If the path doesn't start with 'products/', add it
            if (!str_starts_with($oldPath, 'products/')) {
                $newPath = 'products/' . basename($oldPath);

                // Check if the file exists in storage
                if (Storage::disk('public')->exists($oldPath)) {
                    // Move the file to the new location if needed
                    Storage::disk('public')->move($oldPath, $newPath);
                }

                $image->image_name = $newPath;
                $image->save();
                $count++;
            }
        }

        $this->info("Updated {$count} product image paths.");
    }
}
