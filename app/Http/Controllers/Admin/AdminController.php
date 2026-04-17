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
     * Display orders index.
     * Shows all orders (placeholder - no orders table yet).
     */
    public function orders()
    {
        // Placeholder: In real system, would fetch from orders table
        $orders = [];
        $total_orders = 0;
        $total_revenue = 0;

        return view('admin.orders.index', [
            'orders' => $orders,
            'total_orders' => $total_orders,
            'total_revenue' => $total_revenue,
        ]);
    }

    /**
     * Display contact message replies management.
     * Shows pending and replied messages for admin to manage.
     */
    public function replies(Request $request)
    {
        $filter = $request->query('filter', 'all'); // all, pending, read, replied
        
        $query = ContactMessage::with('user');

        // Apply filter
        switch ($filter) {
            case 'pending':
                $query->pending();
                break;
            case 'read':
                $query->read();
                break;
            case 'replied':
                $query->replied();
                break;
        }

        $messages = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        $counts = [
            'all' => ContactMessage::count(),
            'pending' => ContactMessage::pending()->count(),
            'read' => ContactMessage::read()->count(),
            'replied' => ContactMessage::replied()->count(),
        ];

        return view('admin.contact.replies', [
            'messages' => $messages,
            'counts' => $counts,
            'current_filter' => $filter,
        ]);
    }

    /**
     * Show a single contact message for replying.
     */
    public function showReply(ContactMessage $message)
    {
        // Mark as read if pending
        if ($message->status === 'pending') {
            $message->markAsRead();
        }

        return view('admin.contact.reply-detail', ['message' => $message]);
    }

    /**
     * Store reply to a contact message.
     */
    public function storeReply(Request $request, ContactMessage $message)
    {
        $validated = $request->validate([
            'admin_reply' => [
                'required',
                'string',
                'min:10',
                'max:5000',
            ],
        ]);

        $message->markAsReplied($validated['admin_reply']);

        return redirect()->route('admin.contact.replies')
            ->with('success', 'Reply sent to ' . $message->user->name . '!');
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
        return $dataTable->render('products.datatables');
    }

    /**
     * Display Orders DataTable.
     */
    public function ordersDataTable(OrderDataTable $dataTable)
    {
        return $dataTable->render('orders.datatables');
    }
}
