<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

try {
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    echo "=== Testing OrderDataTable with HTML rendering ===\n\n";
    
    $datatable = new \App\DataTables\OrderDataTable();
    $order_model = new \App\Models\Order();
    $query = $datatable->query($order_model);
    
    $dt = new \Yajra\DataTables\EloquentDataTable($query);
    
    // Add columns as defined in dataTable method
    $result = $dt->addColumn('checkbox', function ($row) {
            return '<input type=\"checkbox\" class=\"order-checkbox\" value=\"' . $row->id . '\">';
        })
        ->addColumn('customer_name', function ($row) {
            return $row->user ? $row->user->name : 'Guest';
        })
        ->addColumn('action', function ($row) {
            return '<a href=\"#\">View</a>';
        })
        ->editColumn('total', function ($row) {
            return '$' . number_format($row->total, 2);
        })
        ->editColumn('status', function ($row) {
            $statusColors = [
                'pending' => 'warning',
                'processing' => 'info',
                'completed' => 'success',
                'cancelled' => 'danger',
            ];
            $color = $statusColors[$row->status] ?? 'secondary';
            return '<span class=\"badge bg-' . $color . '\">' . ucfirst($row->status) . '</span>';
        })
        ->editColumn('created_at', function ($row) {
            return $row->created_at->format('M d, Y H:i');
        })
        ->setRowId('id')
        ->rawColumns(['checkbox', 'status', 'action']);
    
    $json = $result->make(true);
    
    echo "DataTable JSON response:\n";
    echo $json . "\n\n";
    
    echo "✓ OrderDataTable rendering successful!\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n\n";
    echo $e->getTraceAsString();
}
