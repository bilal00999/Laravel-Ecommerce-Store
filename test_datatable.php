<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

try {
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    echo "=== Testing OrderDataTable ===\n\n";
    
    $datatable = new \App\DataTables\OrderDataTable();
    $order_model = new \App\Models\Order();
    $query = $datatable->query($order_model);
    
    echo "Query: " . $query->toSql() . "\n";
    echo "Bindings: " . json_encode($query->getBindings()) . "\n\n";
    
    $orders = $query->limit(1)->get();
    echo "Order count: " . $orders->count() . "\n\n";
    
    if ($orders->count() > 0) {
        $first = $orders->first();
        echo "First order: \n";
        echo "  ID: " . $first->id . "\n";
        echo "  Total: " . $first->total . "\n";
        echo "  Status: " . $first->status . "\n";
        echo "  User: " . ($first->user ? $first->user->name : 'null') . "\n";
    } else {
        echo "No orders found.\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n\n";
    echo $e->getTraceAsString();
}
