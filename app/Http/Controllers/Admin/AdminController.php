<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\ProductDataTable;
use App\DataTables\OrderDataTable;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with visitor overview.
     * Shows statistics and metrics.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_messages' => ContactMessage::count(),
            'pending_messages' => ContactMessage::pending()->count(),
            'admin_users' => User::where('is_admin', true)->count(),
        ];

        // Recent activity
        $recent_messages = ContactMessage::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recent_users = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recent_products = Product::with('category', 'user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recent_messages' => $recent_messages,
            'recent_users' => $recent_users,
            'recent_products' => $recent_products,
        ]);
    }

    /**
     * Display Orders index page (render the page with DataTable).
     */
    public function orders(OrderDataTable $dataTable)
    {
        // This returns the view which will initialize the DataTable
        // The DataTable JavaScript will then make AJAX calls to the datatable endpoint
        return $dataTable->render('orders.datatables');
    }
    /**
     * Display products index (placeholder for products page).
     */
    public function products(ProductDataTable $dataTable)
    {
        // This returns the view which will initialize the DataTable
        // The DataTable JavaScript will then make AJAX calls to the datatable endpoint
        return $dataTable->render('products.datatables');
    }
    /**
     * Display Orders DataTable (AJAX endpoint returning JSON).
     */
    public function ordersDataTable(OrderDataTable $dataTable)
    {
        return $dataTable->dataTable($dataTable->query(new \App\Models\Order()))
            ->make(true);
    }

    /**
     * Visitor overview - detailed analytics (dashboard component).
     */
    public function visitorOverview()
    {
        $stats = [
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'active_users' => User::whereNotNull('email_verified_at')->count(),
        ];

        $users_by_date = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return view('admin.visitors.overview', [
            'stats' => $stats,
            'users_by_date' => $users_by_date,
        ]);
    }

    /**
     * Display Products DataTable.
     */
    public function productsDataTable(ProductDataTable $dataTable)
    {
        return $dataTable->dataTable($dataTable->query(new \App\Models\Product()))
            ->make(true);
    }

    /**
     * Show order details page
     */
    public function showOrder(\App\Models\Order $order)
    {
        $order->load('user', 'items');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateOrder(Request $request, \App\Models\Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Bulk update order status
     */
    public function bulkUpdateOrders(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'integer|exists:orders,id',
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        \App\Models\Order::whereIn('id', $validated['order_ids'])
            ->update(['status' => $validated['status']]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Orders updated successfully.');
    }
}
