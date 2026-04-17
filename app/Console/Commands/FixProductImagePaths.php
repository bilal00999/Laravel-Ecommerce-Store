<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class FixProductImagePaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-product-image-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert image paths to storage/products/filename format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing product image paths...');
        
        $products = Product::whereNotNull('image_path')->get();
        
        if ($products->isEmpty()) {
            $this->info('No products with image paths found.');
            return;
        }
        
        $updated = 0;
        
        foreach ($products as $product) {
            $currentPath = $product->image_path;
            $newPath = null;
            
            // Already in correct format
            if (str_starts_with($currentPath, 'storage/products/')) {
                continue;
            }
            
            // Convert from /storage/products/... to storage/products/...
            if (str_starts_with($currentPath, '/storage/')) {
                $newPath = ltrim($currentPath, '/');
            }
            // Convert from storage/app/public/products/... to storage/products/...
            elseif (str_contains($currentPath, 'app/public/')) {
                $newPath = str_replace('storage/app/public/', 'storage/', $currentPath);
            }
            // Extract filename from any URL format and rebuild path
            elseif (preg_match('/([a-zA-Z0-9_\-\.]+\.(jpg|jpeg|png|gif))/i', $currentPath, $matches)) {
                $newPath = 'storage/products/' . $matches[1];
            }
            
            if ($newPath && $newPath !== $currentPath) {
                $product->update(['image_path' => $newPath]);
                $updated++;
                $this->line("✓ Updated: {$product->name} → {$newPath}");
            }
        }
        
        $this->info("✓ Successfully updated {$updated} product image paths.");
    }
}

