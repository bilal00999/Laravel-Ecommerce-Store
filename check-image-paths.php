<?php
// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get database connection
use Illuminate\Support\Facades\DB;

echo "=== CHECKING FIRST 3 PRODUCTS ===" . PHP_EOL . PHP_EOL;

$products = DB::table('products')->limit(3)->get();

if ($products->isEmpty()) {
    echo "No products found in database." . PHP_EOL;
} else {
    foreach ($products as $index => $product) {
        echo "Product #" . ($index + 1) . ":" . PHP_EOL;
        echo "  ID: " . $product->id . PHP_EOL;
        echo "  Name: " . $product->name . PHP_EOL;
        echo "  Image Path (from DB): " . $product->image_path . PHP_EOL;
        echo "  Image Path Type: " . gettype($product->image_path) . PHP_EOL;
        echo "  Image Path Length: " . strlen($product->image_path) . " chars" . PHP_EOL;
        
        // Check if file exists at that path
        $filePath = $product->image_path;
        $fileExists = file_exists($filePath);
        echo "  File Exists: " . ($fileExists ? "YES" : "NO") . PHP_EOL;
        
        // Try with public path prefix
        $publicPath = public_path($filePath);
        $publicExists = file_exists($publicPath);
        echo "  File Exists (with public_path): " . ($publicExists ? "YES" : "NO") . PHP_EOL;
        echo "  Full path checked: " . $publicPath . PHP_EOL;
        
        // Show raw bytes (for debugging encoding issues)
        echo "  Raw bytes: " . bin2hex($filePath) . PHP_EOL;
        
        echo PHP_EOL;
    }
}

echo "=== END ===" . PHP_EOL;
?>
