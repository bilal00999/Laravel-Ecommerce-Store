# Quick Start Guide - Checkout & Admin Panel

## 🚀 Getting Started

Your checkout system and admin panel are ready to use! Follow these steps to test:

---

## 📝 Test Checkout Flow

### 1. Create Test Data (if needed)

- Visit: `http://localhost:8000/products`
- View any product or create new ones
- Add a product to cart

### 2. Access Cart

- Click on cart in navbar
- URL: `http://localhost:8000/cart`
- Click "Proceed to Checkout" button

### 3. Login (if not already)

- If prompted, login with your test account
- Default test user: `admin@example.com` / `password`

### 4. Fill Checkout Form

- **Full Name:** John Doe
- **Email:** john@example.com
- **Phone:** +1 (555) 123-4567
- **Street:** 123 Main Street
- **City:** New York
- **State:** NY
- **Postal Code:** 10001
- **Country:** USA
- **Payment Method:** Credit Card (select one)
- **Check:** Accept Terms & Conditions

### 5. Place Order

- Click "Place Order" button
- Should see success page at `/checkout/success/{order_id}`

### 6. View Orders

- Click Account dropdown in navbar
- Select "My Orders"
- URL: `http://localhost:8000/orders`
- See your order in the list
- Click "View Details" to see full order info

---

## 👨‍💼 Test Admin Panel

### 1. Access Admin Dashboard

- URL: `http://localhost:8000/admin`
- Must be logged in as admin user
- Default: `admin@example.com`

### 2. View Orders DataTable

- Click "Orders" in sidebar or visit: `/admin/orders/datatable`
- Features to test:
    - **Search:** Type order ID or customer name
    - **Sort:** Click column headers
    - **Pagination:** Navigate pages
    - **Export:** Click Excel/CSV/PDF buttons
    - **Status badges:** Check color-coded status display

### 3. View Products DataTable

- Click "Products (DataTable)" in sidebar or visit: `/admin/products/datatable`
- Features to test:
    - **Search:** Type product name
    - **Sort:** Click column headers
    - **Price format:** Should show as $X.XX
    - **Stock status:** In Stock (green) or Out of Stock (red)
    - **Export:** Download in multiple formats
    - **Actions:** View, Edit, Delete buttons

---

## 🔗 URLs Reference

### **Public URLs (for users)**

```
GET  /cart                          → Shopping cart
GET  /products                      → Product listing
GET  /products/{id}                 → Product details
GET  /checkout                      → Checkout form
POST /checkout                      → Process order
GET  /checkout/success/{order}      → Order confirmation
GET  /orders                        → Order history
GET  /orders/{order}                → Order details
```

### **Admin URLs**

```
GET  /admin                                → Dashboard
GET  /admin/products/datatable            → Products DataTable
GET  /admin/orders/datatable              → Orders DataTable
GET  /admin/contact/replies               → Messages
GET  /admin/visitors                      → Analytics
GET  /admin/settings                      → Settings
```

---

## 💾 Database Tables

### Orders Table

```sql
SELECT * FROM orders;
-- Columns: id, user_id, total, status, payment_method, shipping_address, notes
```

### Order Items Table

```sql
SELECT * FROM order_items;
-- Columns: id, order_id, product_id, quantity, unit_price, total
```

### View Sample Data

```sql
-- All orders by a user
SELECT * FROM orders WHERE user_id = 2;

-- All items in an order
SELECT oi.*, p.name FROM order_items oi
JOIN products p ON oi.product_id = p.id
WHERE oi.order_id = 1;
```

---

## ✅ Verification Checklist

Run these commands to verify everything is set up correctly:

```bash
# 1. Check routes are registered
php artisan route:list | grep -E "(checkout|orders|datatable)"

# 2. Check models exist
php artisan tinker
>>> Order::count()
>>> OrderItem::count()

# 3. Check migrations ran
php artisan migrate:status

# 4. Check DataTable classes exist
>>> file_exists(app_path('DataTables/ProductDataTable.php'))
>>> file_exists(app_path('DataTables/OrderDataTable.php'))
```

---

## 🐛 Troubleshooting

### "Route not found" Error

- Run: `php artisan route:clear`
- Run: `composer dump-autoload`

### "Table doesn't exist" Error

- Run: `php artisan migrate`
- Check: `php artisan migrate:status`

### "Class not found" Error

- Run: `composer dump-autoload`
- Run: `php artisan config:cache`

### DataTable not displaying

- Open browser DevTools (F12)
- Check Console tab for JavaScript errors
- Check Network tab - look for failed AJAX requests
- Verify data exists: `php artisan tinker >>> Order::all()`

### Checkout button not visible

- Make sure you're logged in
- Cart must not be empty
- Clear browser cache (Ctrl+Shift+Delete)

---

## 🎨 Customization Examples

### Change Order Status Display Colors

Edit: `app/DataTables/OrderDataTable.php`

```php
->editColumn('status', function ($row) {
    $statusColors = [
        'pending' => 'warning',
        'processing' => 'info',
        'completed' => 'success',
        'cancelled' => 'danger',
    ];
    $color = $statusColors[$row->status] ?? 'secondary';
    return '<span class="badge bg-' . $color . '">' . ucfirst($row->status) . '</span>';
})
```

### Change Tax Rate

Edit: `app/Http/Controllers/CheckoutController.php`

```php
$tax = $subtotal * 0.10;  // Change 0.08 to desired rate
```

### Add More Payment Methods

Edit: `resources/views/checkout/show.blade.php`

```blade
<div class="form-check">
    <input type="radio" name="payment_method" value="cryptocurrency">
    <label>Bitcoin</label>
</div>
```

### Customize Order Email

Create: `resources/views/emails/order-confirmation.blade.php`
Then send in CheckoutController after order creation.

---

## 📞 Support Features

### View Application Logs

```bash
tail -f storage/logs/laravel.log
```

### Debug Mode

Edit: `.env`

```
APP_DEBUG=true
```

### Test Email Sending

```bash
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com'); })
```

---

## 🎯 Next Development Tasks

1. **[ ] Integrate Payment Gateway**
    - Choose provider (Stripe, PayPal, Square)
    - Add payment form
    - Update order status on payment

2. **[ ] Add Order Notifications**
    - Send confirmation email
    - SMS notifications
    - Admin alerts

3. **[ ] Enhance Admin Panel**
    - Update order status manually
    - Send messages to users
    - View order statistics

4. **[ ] Add More Features**
    - Discount codes
    - Order cancellation
    - Refund system
    - Return tracking

5. **[ ] Improve User Experience**
    - Order tracking page
    - Email reminders
    - Wishlist
    - Recent orders widget

---

## 📞 Code Review Points

Key areas to review:

1. **CheckoutController** - Order processing logic
2. **Migrations** - Database schema
3. **Models** - Relationships and validation
4. **Views** - User-facing templates
5. **Routes** - Access control and naming

All code follows Laravel best practices with:

- ✅ Proper type hints
- ✅ Comprehensive comments
- ✅ Error handling
- ✅ Security validation
- ✅ Clean code standards

---

## 💡 Tips & Tricks

### Quickly Access Admin Panel

```
http://localhost:8000/admin
```

### View All Orders

```
php artisan tinker
>>> Order::with('user', 'items')->get()
```

### Create Test Order Programmatically

```bash
php artisan tinker
>>> $user = User::find(2)
>>> Order::create(['user_id' => $user->id, 'total' => 299.99])
```

### Clear All Orders

```bash
php artisan tinker
>>> Order::truncate()
>>> OrderItem::truncate()
```

### Export Orders to CSV

Use the DataTable export button on `/admin/orders/datatable`

---

**Ready to go!** 🚀 Start testing your checkout system now!

For more details, see: `CHECKOUT_SETUP_GUIDE.md`
