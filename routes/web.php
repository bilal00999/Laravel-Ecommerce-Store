<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ContactController;

// Public routes - Redirect home to products
Route::get('/', function () {
    return redirect()->route('products.index');
})->name('home');

/**
 * MIDDLEWARE USAGE EXAMPLES
 * 
 * 1. 'auth' - Redirect unauthenticated users to login
 *    Usage: Route::middleware('auth')->group(...) or ->middleware('auth')
 *
 * 2. 'guest' - Redirect already-logged-in users away from login/register
 *    Usage: Route::middleware('guest')->group(...) or ->middleware('guest')
 *
 * 3. 'admin' - Allow access only to users with role = 'admin'
 *    Usage: Route::middleware('admin')->group(...) or ->middleware('admin')
 *
 * You can also combine multiple middleware:
 *    ->middleware(['auth', 'verified', 'admin'])
 */

// ============================================================
// 1. GUEST MIDDLEWARE - Routes for unauthenticated users only
// ============================================================
// Redirects logged-in users to dashboard
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.web');
    
    // Registration
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// ============================================================
// 2. AUTH MIDDLEWARE - Routes for authenticated users
// ============================================================
// Redirects unauthenticated users to login
Route::middleware('auth')->group(function () {
    // Logout - accessible to all authenticated users
    Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

    // Dashboard redirect to products
    Route::get('/dashboard', function () {
        return redirect()->route('products.index');
    })->name('dashboard');
});

// ============================================================
// 3. ADMIN MIDDLEWARE - Routes for admin users only
// ============================================================
// Checks if user is authenticated AND has role = 'admin'

Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    
    // Products
    Route::get('/products', [AdminController::class, 'products'])->name('products.index');
    
    // DataTables
    Route::match(['get', 'post'], '/products/datatable', [AdminController::class, 'productsDataTable'])->name('products.datatable');
    Route::match(['get', 'post'], '/orders/datatable', [AdminController::class, 'ordersDataTable'])->name('orders.datatable');
    
    // Contact Message Replies
    Route::get('/contact/replies', [AdminController::class, 'replies'])->name('contact.replies');
    Route::get('/contact/{contactMessage}', [AdminController::class, 'showReply'])->name('contact.show');
    Route::post('/contact/{contactMessage}/reply', [AdminController::class, 'storeReply'])->name('contact.reply.store');
    
    // Visitor Overview
    Route::get('/visitors', [AdminController::class, 'visitorOverview'])->name('visitors.overview');
    
    // Settings
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
});

// ============================================================
// 4. GATES AND POLICIES - Role-based access control
// ============================================================
// 
// GATES: Simple authorization checks defined in AuthServiceProvider
// - Usage 1: if (Gate::allows('admin')) { ... }
// - Usage 2: @can('admin') in Blade templates
// - Usage 3: $this->authorize('admin') in controllers
//
// POLICIES: Model-specific authorization rules
// - Used for model-based authorization (ProductPolicy, etc.)
// - Applied via: $this->authorize('create', Product::class)
// - Applied via: @can('update', $product) in Blade
//
// Example Gates defined in AuthServiceProvider:
// - 'admin' - Check if user is admin
// - 'moderator' - Check if user is admin or moderator
// - 'manage-users' - Only admins
// - 'manage-settings' - Only admins
// - 'view-analytics' - Admins and moderators
//

// ============================================================
// PRODUCT ROUTES - Demonstrating Policies
// ============================================================

// Public product listing - Everyone can view
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Product management - Requires authentication and policies
Route::middleware('auth')->group(function () {
    // Create product - Only if policy allows (admin users)
    // ProductPolicy::create() checks: user->role === 'admin'
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    // Edit product - Only if policy allows (admin or product owner)
    // ProductPolicy::update() checks: admin OR owner
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

    // Delete product - Only if policy allows (admin or product owner)
    // ProductPolicy::delete() checks: admin OR owner
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Product image upload - Only if user is admin or product owner
    Route::get('/products/{product}/upload-image', [ProductImageController::class, 'edit'])->name('products.edit-image');
    Route::post('/products/{product}/upload-image', [ProductImageController::class, 'store'])->name('products.upload-image');
});

// ============================================================
// SHOPPING CART ROUTES
// ============================================================

// Public cart routes (accessible to everyone - guests and logged in users)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');

// Cart management (for both guests and authenticated users)
Route::middleware('web')->group(function () {
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// ============================================================
// CHECKOUT ROUTES - Protected by 'auth' middleware
// ============================================================
// Only authenticated users can checkout

Route::middleware('auth')->group(function () {
    // Show checkout form
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    
    // Process checkout (create order)
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Order success page
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // User's order history
    Route::get('/orders', [CheckoutController::class, 'orders'])->name('checkout.orders');
    
    // View specific order details
    Route::get('/orders/{order}', [CheckoutController::class, 'orderDetails'])->name('checkout.order-details');
});

// ============================================================
// CONTACT FORM ROUTES - Protected by 'auth' middleware
// ============================================================
// Only authenticated (logged-in) users can access contact form

Route::middleware('auth')->group(function () {
    // Show contact form
    Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
    
    // Store contact message
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    
    // View message history (all messages sent by logged-in user)
    Route::get('/contact/history', [ContactController::class, 'history'])->name('contact.history');
    
    // View specific contact message
    Route::get('/contact/{contactMessage}', [ContactController::class, 'showMessage'])->name('contact.message');
});

// ============================================================
// ADMIN GATE EXAMPLE ROUTES
// ============================================================

Route::middleware(['auth', 'verified'])->group(function () {
    // Using Gate::allows() in controller
    Route::get('/admin/stats', [ProductController::class, 'adminStats'])->name('admin.stats');

    // Using Gate::denies() in controller
    Route::get('/managers-area', [ProductController::class, 'managersOnly'])->name('managers.area');

    // Using Gate::authorize() in controller
    Route::get('/admin/settings', [ProductController::class, 'settingsPage'])->name('admin.settings.page');
});

// ============================================================
// ALTERNATIVE: Apply middleware directly to routes
// ============================================================
/*
// Single middleware
Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('auth');

// Multiple middleware (applied in order)
Route::post('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])
    ->middleware(['auth', 'admin']);

// Conditional middleware with parameters
Route::get('/posts/{id}', [PostController::class, 'show'])
    ->middleware('throttle:60,1');
*/

