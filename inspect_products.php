<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "=== First 5 Products ===\n";
$products = Product::limit(5)->get();

foreach ($products as $product) {
    echo "\n--- Product ID: " . $product->id . " ---\n";
    echo "Name: " . $product->name . "\n";
    echo "image_path RAW value:\n";
    var_dump($product->image_path);
    echo "Null check: " . (is_null($product->image_path) ? "NULL" : "NOT NULL") . "\n";
    echo "Empty check: " . (empty($product->image_path) ? "EMPTY" : "NOT EMPTY") . "\n";
    if ($product->image_path) {
        echo "Value length: " . strlen($product->image_path) . "\n";
    }
}
?>
