<?php
/**
 * EXAMPLE: Add these routes to routes/web.php to integrate DataTables into your admin panel
 * 
 * Add the DataTableController import at the top of routes/web.php:
 * use App\Http\Controllers\Admin\DataTableController;
 * 
 * Then add these routes inside your admin route group:
 */

// Example admin route group with DataTable routes
/*
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Existing routes...
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // ADD THESE NEW DATATABLE ROUTES:
        Route::get('/products/datatable', [DataTableController::class, 'products'])
            ->name('products.datatable');
        
        Route::get('/orders/datatable', [DataTableController::class, 'orders'])
            ->name('orders.datatable');
        
        // ... rest of your admin routes
    });
*/

/**
 * QUICK SETUP STEPS:
 * 
 * 1. Add import at top of routes/web.php:
 *    use App\Http\Controllers\Admin\DataTableController;
 * 
 * 2. Add routes inside your admin route group (shown above)
 * 
 * 3. Run migrations to create orders tables:
 *    php artisan migrate
 * 
 * 4. Add navigation links in resources/views/admin/layout.blade.php:
 *    <li>
 *        <a href="{{ route('admin.products.datatable') }}" 
 *           class="nav-link @if(request()->routeIs('admin.products.datatable')) active @endif">
 *            <i class="bi bi-box"></i> Products DataTable
 *        </a>
 *    </li>
 *    <li>
 *        <a href="{{ route('admin.orders.datatable') }}" 
 *           class="nav-link @if(request()->routeIs('admin.orders.datatable')) active @endif">
 *            <i class="bi bi-cart"></i> Orders DataTable
 *        </a>
 *    </li>
 * 
 * 5. Visit /admin/products/datatable and /admin/orders/datatable to see your DataTables!
 */
