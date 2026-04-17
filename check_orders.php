<?php
require_once 'bootstrap/app.php';
\$app = require_once 'bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Http\Kernel::class);
\ = Illuminate\Http\Request::capture();
\ = \->handle(\);

// Import the model
use App\Models\Order;

// Query orders with user data
\ = Order::with('user')->get();

// Display the results
echo "Total Orders: " . count(\) . "\n";
if (\->count() > 0) {
    foreach (\ as \) {
        echo "Order ID: " . \->id . "\n";
        if (\->user) {
            echo "User: " . \->user->name . " (" . \->user->email . ")\n";
        }
        echo "---\n";
    }
} else {
    echo "No orders found.\n";
}
?>
