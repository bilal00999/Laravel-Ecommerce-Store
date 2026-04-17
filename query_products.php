<?php
require 'vendor/autoload.php';
\ = require_once 'bootstrap/app.php';
\->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Query products with image_path
\ = \Illuminate\Support\Facades\DB::table('products')
    ->select('id', 'name', 'image_path', 'created_at')
    ->limit(5)
    ->get();

echo "=== Products with Image Paths ===\n";
echo str_repeat("-", 80) . "\n";
echo sprintf("%-5s | %-30s | %-40s\n", "ID", "Name", "Image Path");
echo str_repeat("-", 80) . "\n";

if (\->count() === 0) {
    echo "No products found in the database.\n";
} else {
    foreach (\ as \) {
        echo sprintf("%-5s | %-30s | %-40s\n", 
            \->id,
            substr(\->name, 0, 28),
            \->image_path ?? 'NULL'
        );
    }
}
echo str_repeat("-", 80) . "\n";
echo "Total products shown: " . \->count() . "\n";
?>
