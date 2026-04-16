# Laravel Middleware Guide

## Overview

This application includes three essential middleware for authentication and authorization:

### 1. **`auth`** - Authentication Middleware

**File**: `app/Http/Middleware/Authenticate.php`

**Purpose**: Redirect unauthenticated users to the login page

**How it works**:

- Checks if user is authenticated
- Returns 401 JSON response for API requests
- Redirects to login for web requests

**Usage in Routes**:

```php
// Single route
Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth');

// Route group
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
```

**Common use case**:

- Protect user dashboard pages
- Protect user profile pages
- Protect any resource that requires login

---

### 2. **`guest`** - Guest Middleware

**File**: `app/Http/Middleware/RedirectIfAuthenticated.php`

**Purpose**: Redirect already-logged-in users away from login/register pages

**How it works**:

- Checks if user is already authenticated
- Redirects to dashboard if already logged in
- Allows unauthenticated users to proceed

**Usage in Routes**:

```php
// Single route
Route::get('/login', [AuthController::class, 'login'])->middleware('guest');

// Route group
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'loginWeb']);
    Route::get('/register', [RegisterController::class, 'show']);
    Route::post('/register', [RegisterController::class, 'store']);
});
```

**Common use case**:

- Prevent logged-in users from accessing login page
- Prevent logged-in users from accessing registration page
- Avoid duplicate login sessions

---

### 3. **`admin`** - Admin Middleware

**File**: `app/Http/Middleware/IsAdmin.php`

**Purpose**: Allow access only to users with `role = 'admin'` or `is_admin = true`

**How it works**:

- Checks if user is authenticated
- Checks if user has admin role
- Returns 403 Forbidden response if not admin
- Supports both web (abort) and API (JSON) responses

**Usage in Routes**:

```php
// Single route
Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])
    ->middleware(['auth', 'admin']);

// Route group
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard']);
    Route::resource('/admin/users', AdminUserController::class);
    Route::resource('/admin/settings', AdminSettingsController::class);
});
```

**Common use case**:

- Protect admin dashboard
- Protect admin panel routes
- Restrict data management operations

---

## Middleware Registration

All middleware are registered in `app/Http/Kernel.php` under `$middlewareAliases`:

```php
protected $middlewareAliases = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'admin' => \App\Http\Middleware\IsAdmin::class,
    // ... other middleware
];
```

---

## Database Schema

The `users` table includes role-based columns:

```sql
-- Added by migration: 2026_04_17_000000_add_role_to_users_table.php

ALTER TABLE users ADD COLUMN role VARCHAR(255) DEFAULT 'user';  -- 'user', 'admin', 'moderator'
ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0;     -- Legacy boolean
ALTER TABLE users ADD COLUMN api_token VARCHAR(255) UNIQUE NULLABLE;
```

---

## User Model

The User model includes role management:

```php
// Fillable attributes
#[Fillable(['name', 'email', 'password', 'role', 'is_admin', 'api_token'])]

// Check if user is admin
if ($user->role === 'admin' || $user->is_admin) {
    // User has admin privileges
}
```

---

## Complete Route Example

```php
// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest-only routes (unauthenticated users)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.web');
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('/admin/users', AdminUserController::class);
    Route::resource('/admin/settings', AdminSettingsController::class);
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy']);
});
```

---

## Setting User Roles

### Creating Admin Users

```php
// Via Tinker
// php artisan tinker
$user = User::find(1);
$user->update(['role' => 'admin', 'is_admin' => true]);

// Or during creation
$admin = User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'admin',
    'is_admin' => true,
]);

// Via migration seed
User::factory()->create([
    'email' => 'admin@example.com',
    'role' => 'admin',
    'is_admin' => true,
]);
```

---

## Combining Multiple Middleware

Middleware are applied in order. Common combinations:

```php
// Require authentication and email verification
->middleware(['auth', 'verified'])

// Require admin role and verified email
->middleware(['auth', 'verified', 'admin'])

// Apply rate limiting
->middleware('throttle:60,1')

// Combine: auth + admin + rate limit
->middleware(['auth', 'admin', 'throttle:60,1'])
```

---

## Middleware Execution Order

When multiple middleware are applied, they execute in the order specified:

```php
Route::get('/admin', Controller::class)->middleware(['auth', 'admin']);

// Execution order:
// 1. 'auth' middleware checks authentication
// 2. If passes, 'admin' middleware checks admin role
// 3. If passes, route handler executes

// If step 1 fails: redirect to login
// If step 2 fails: abort with 403 Forbidden
```

---

## Testing Middleware

### Test Authentication

```php
public function test_guest_cannot_access_dashboard()
{
    $this->get('/dashboard')->assertRedirect('/login');
}

public function test_authenticated_user_can_access_dashboard()
{
    $user = User::factory()->create();
    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk();
}
```

### Test Admin Access

```php
public function test_non_admin_cannot_access_admin_panel()
{
    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($user)
        ->get('/admin')
        ->assertStatus(403);
}

public function test_admin_can_access_admin_panel()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk();
}
```

### Test Guest Middleware

```php
public function test_authenticated_user_redirected_from_login()
{
    $user = User::factory()->create();
    $this->actingAs($user)
        ->get('/login')
        ->assertRedirect('/dashboard');
}
```

---

## Troubleshooting

### Issue: Middleware not applying

- Check route is using correct middleware name
- Verify middleware is registered in `Kernel.php`
- Ensure class namespace is correct

### Issue: User being redirected to login

- Confirm user is authenticated
- Check session is being set properly
- Verify auth guard is configured correctly

### Issue: 403 Forbidden on admin routes

- Confirm user has `role = 'admin'`
- Check if `is_admin` flag is set
- Verify middleware is applied: `->middleware('admin')`

---

## Files Created/Modified

1. ✅ `app/Http/Middleware/Authenticate.php` - NEW
2. ✅ `app/Http/Middleware/RedirectIfAuthenticated.php` - NEW
3. ✅ `app/Http/Middleware/IsAdmin.php` - UPDATED
4. ✅ `app/Http/Kernel.php` - Already configured
5. ✅ `app/Models/User.php` - Updated with role field
6. ✅ `routes/web.php` - Example usage
7. ✅ `database/migrations/2026_04_17_000000_add_role_to_users_table.php` - NEW
