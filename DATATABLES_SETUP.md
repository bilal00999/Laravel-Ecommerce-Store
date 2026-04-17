# Yajra DataTables Integration Guide

## Installation Summary

Yajra DataTables has been successfully installed in your Laravel application.

**Package Installed:** `yajra/laravel-datatables` v13.0.0

### Additional Packages Installed:

- yajra/laravel-datatables-buttons - Export functionality
- yajra/laravel-datatables-html - HTML builder
- yajra/laravel-datatables-oracle - Database driver support
- yajra/laravel-datatables-editor - Inline editing (optional)
- yajra/laravel-datatables-export - Export features

## What's Included

### 1. DataTable Classes

#### ProductDataTable

- **Location:** `app/DataTables/ProductDataTable.php`
- **Features:**
    - Server-side pagination
    - Sorting by any column
    - Search functionality
    - Price formatting ($X.XX)
    - Stock status badge (In Stock/Out of Stock)
    - Export to Excel, CSV, PDF
    - Print functionality
    - Action buttons (View, Edit, Delete)

#### OrderDataTable

- **Location:** `app/DataTables/OrderDataTable.php`
- **Features:**
    - Server-side pagination for orders
    - Customer name display (with relationship)
    - Order total formatting
    - Status badges (pending, processing, completed, cancelled)
    - Order date formatting
    - Export capabilities
    - Action buttons (View Details, Edit)

### 2. Models

#### Order Model

- **Location:** `app/Models/Order.php`
- **Relationships:**
    - `belongsTo(User::class)` - Order owner
    - `hasMany(OrderItem::class)` - Order items

#### OrderItem Model

- **Location:** `app/Models/OrderItem.php`
- **Relationships:**
    - `belongsTo(Order::class)` - Parent order
    - `belongsTo(Product::class)` - Product reference

### 3. Blade Views

#### Products DataTable View

- **Location:** `resources/views/products/datatables.blade.php`
- Includes all required CSS/JS libraries
- Renders ProductDataTable with Bootstrap 5 styling

#### Orders DataTable View

- **Location:** `resources/views/orders/datatables.blade.php`
- Same structure as products with order-specific display

#### Action Templates

- **Products Actions:** `resources/views/products/action.blade.php`
- **Orders Actions:** `resources/views/orders/action.blade.php`
- Display action buttons for each row

### 4. Database Migrations

#### Orders Table

- **Location:** `database/migrations/2026_04_17_155000_create_orders_table.php`
- **Columns:**
    - id (PK)
    - user_id (FK to users)
    - total (decimal 10,2)
    - status (enum: pending, processing, completed, cancelled)
    - payment_method
    - shipping_address
    - notes
    - timestamps

#### Order Items Table

- **Location:** `database/migrations/2026_04_17_155001_create_order_items_table.php`
- **Columns:**
    - id (PK)
    - order_id (FK to orders)
    - product_id (FK to products)
    - quantity (int)
    - unit_price (decimal 10,2)
    - total (decimal 10,2)
    - timestamps

## How to Use

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Add Routes to Display DataTables

Add these routes to `routes/web.php`:

```php
// Products DataTable
Route::get('/products/datatable', function () {
    $dataTable = new \App\DataTables\ProductDataTable();
    return $dataTable->render('products.datatables');
})->middleware('auth');

// Orders DataTable
Route::get('/orders/datatable', function () {
    $dataTable = new \App\DataTables\OrderDataTable();
    return $dataTable->render('orders.datatables');
})->middleware('auth');
```

### 3. Or Add to Controller

```php
<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\DataTables\OrderDataTable;

class DataTableController extends Controller
{
    public function products(ProductDataTable $dataTable)
    {
        return $dataTable->render('products.datatables');
    }

    public function orders(OrderDataTable $dataTable)
    {
        return $dataTable->render('orders.datatables');
    }
}
```

Routes:

```php
Route::get('/products/datatable', [DataTableController::class, 'products'])->middleware('auth');
Route::get('/orders/datatable', [DataTableController::class, 'orders'])->middleware('auth');
```

### 4. Customize DataTables

#### Modify Columns

In `ProductDataTable.php`, edit the `getColumns()` method:

```php
public function getColumns(): array
{
    return [
        Column::make('id'),
        Column::make('name'),
        Column::make('price'),
        // Add more columns as needed
    ];
}
```

#### Add Custom Formatting

In the `dataTable()` method:

```php
->editColumn('price', function ($row) {
    return '$' . number_format($row->price, 2);
})
->editColumn('status', function ($row) {
    return '<span class="badge bg-' . ($row->active ? 'success' : 'danger') . '">'
        . ($row->active ? 'Active' : 'Inactive') . '</span>';
})
->rawColumns(['status']) // Tell DataTables which columns contain HTML
```

#### Add Filters

Modify the `query()` method:

```php
public function query(Product $model): QueryBuilder
{
    return $model->newQuery()
        ->with('category')
        ->when(request('category_id'), function ($q) {
            $q->where('category_id', request('category_id'));
        });
}
```

## Features Available

### Export Options

- **Excel** - Download as .xlsx
- **CSV** - Download as comma-separated values
- **PDF** - Download as PDF document
- **Print** - Print-friendly format

### Built-in Actions

- **Reload** - Refresh table data
- **Reset** - Clear all filters and sorting
- **Search** - Global search across all columns
- **Pagination** - Navigate through pages (default 10 per page)
- **Sorting** - Click column headers to sort

### Customization Options

1. **Change Rows Per Page**
   In DataTable class:

    ```php
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->pageLength(25); // Default rows
    }
    ```

2. **Enable/Disable Buttons**

    ```php
    ->buttons([
        Button::make('create'),
        Button::make('excel'),
        Button::make('csv'),
        // Add only needed buttons
    ])
    ```

3. **Add Row Selection**
    ```php
    ->selectStyleSingle() // Single row select
    // or
    ->selectStyleMulti() // Multiple row select
    ```

## Required JavaScript & CSS

All assets are included in the view templates:

### CSS Libraries

- DataTables Bootstrap 5 CSS
- DataTables Buttons CSS

### JavaScript Libraries

- jQuery 3.6.0
- DataTables 1.13.6
- DataTables Bootstrap 5
- DataTables Buttons
- JSZip (for Excel export)
- pdfMake (for PDF export)

All assets are loaded from CDN for easy distribution.

## Troubleshooting

### DataTable Not Showing

1. Ensure migrations are run: `php artisan migrate`
2. Check that data exists in the database
3. Verify routes are correctly registered: `php artisan route:list`

### Export Not Working

1. Ensure JSZip and pdfMake scripts are loaded
2. Check browser console for errors
3. Verify file permissions for downloads

### AJAX Errors

1. Check browser Network tab for failed requests
2. Verify CSRF token is present (Laravel includes this automatically)
3. Check Laravel logs: `storage/logs/laravel.log`

## Integration with Admin Panel

To integrate DataTables into your admin panel:

1. **Add to Admin Routes:**

    ```php
    Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/products/datatable', [AdminController::class, 'productsDataTable'])->name('products.datatable');
        Route::get('/orders/datatable', [AdminController::class, 'ordersDataTable'])->name('orders.datatable');
    });
    ```

2. **Add Methods to AdminController:**

    ```php
    use App\DataTables\ProductDataTable;
    use App\DataTables\OrderDataTable;

    public function productsDataTable(ProductDataTable $dataTable)
    {
        return $dataTable->render('products.datatables');
    }

    public function ordersDataTable(OrderDataTable $dataTable)
    {
        return $dataTable->render('orders.datatables');
    }
    ```

3. **Add Links in Admin Layout:**
    ```blade
    <a href="{{ route('admin.products.datatable') }}" class="nav-link">
        <i class="bi bi-box"></i> Products
    </a>
    <a href="{{ route('admin.orders.datatable') }}" class="nav-link">
        <i class="bi bi-cart"></i> Orders
    </a>
    ```

## Next Steps

1. Run migrations to create orders and order_items tables
2. Add DataTable routes to your routes file
3. Test DataTables in your browser
4. Customize columns and formatting as needed
5. Add DataTables links to your admin navigation

For more information, visit: https://yajrabox.com/docs/laravel-datatables/10.0
