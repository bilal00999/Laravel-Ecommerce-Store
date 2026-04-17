<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Query products with image_path
$products = Illuminate\Support\Facades\DB::table('products')
    ->select('id', 'name', 'image_path')
    ->limit(5)
    ->get();

echo "=== Products with Image Paths ===\n";
echo str_repeat('-', 90) . "\n";
printf('%-5s | %-30s | %-50s' . "\n", 'ID', 'Name', 'Image Path');
echo str_repeat('-', 90) . "\n";

if (count($products) === 0) {
    echo "No products found in the database.\n";
} else {
    foreach ($products as $product) {
        printf('%-5s | %-30s | %-50s' . "\n", 
            $product->id,
            substr($product->name, 0, 28),
            $product->image_path ?? 'NULL'
        );
    }
}
echo str_repeat('-', 90) . "\n";
echo "Total products shown: " . count($products) . "\n";
?>
