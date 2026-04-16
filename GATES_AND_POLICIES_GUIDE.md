# Laravel Gates and Policies Guide

## Overview

This application uses Laravel's authorization features for role-based access control:

- **Gates**: Simple authorization checks for specific abilities
- **Policies**: Model-specific authorization rules

---

## Part 1: Gates

### What are Gates?

Gates are simple authorization checks in `AuthServiceProvider` that determine if a user can perform an action.

### Defining Gates

**File**: `app/Providers/AuthServiceProvider.php`

```php
use Illuminate\Support\Facades\Gate;

public function boot(): void
{
    // Simple admin gate
    Gate::define('admin', function (User $user) {
        return $user->role === 'admin' || $user->is_admin;
    });

    // Moderator gate (admin or moderator)
    Gate::define('moderator', function (User $user) {
        return in_array($user->role, ['admin', 'moderator']);
    });

    // Specific permission
    Gate::define('manage-users', function (User $user) {
        return $user->role === 'admin';
    });
}
```

### Using Gates in Controllers

**Syntax 1: Using `$this->authorize()`**

```php
public function adminPanel()
{
    // Throws AuthorizationException if check fails
    $this->authorize('admin');

    return view('admin.panel');
}
```

**Syntax 2: Using `Gate::allows()`**

```php
public function statistics()
{
    if (Gate::allows('view-analytics')) {
        $data = collect([/* analytics data */]);
    } else {
        $data = null;
    }

    return view('stats', ['data' => $data]);
}
```

**Syntax 3: Using `Gate::denies()`**

```php
public function settingsPage()
{
    if (Gate::denies('manage-settings')) {
        throw new AuthorizationException();
    }

    return view('settings');
}
```

**Syntax 4: Using `Gate::authorize()`**

```php
public function deleteUser($userId)
{
    // Similar to $this->authorize(), throws exception if denied
    Gate::authorize('manage-users');

    User::destroy($userId);
}
```

**Syntax 5: Using `auth()->user()->can()`**

```php
public function viewSensitiveData()
{
    if (auth()->user()->can('admin')) {
        // Show sensitive data
    }
}
```

### Using Gates in Blade Templates

```blade
{{-- Check if user can perform action --}}
@can('admin')
    <div class="admin-panel">
        <h3>Admin Controls</h3>
        {{-- admin-only content --}}
    </div>
@endcan

{{-- Alternative syntax --}}
@if (Gate::allows('admin'))
    <p>You have admin access</p>
@endif

{{-- Opposite: @cannot --}}
@cannot('admin')
    <p>You don't have admin access</p>
@endcannot

{{-- Alternative: Gate::denies() --}}
@if (Gate::denies('admin'))
    <p>You don't have admin permissions</p>
@endif
```

### Available Gates

These gates are defined in `AuthServiceProvider`:

- **`admin`** - User has admin role
- **`moderator`** - User is admin or moderator
- **`manage-users`** - User can manage users (admin only)
- **`manage-settings`** - User can access settings (admin only)
- **`view-analytics`** - User can view analytics (admin/moderator)

---

## Part 2: Policies

### What are Policies?

Policies are classes that organize authorization logic for a specific model. They group related authorization checks for create, read, update, delete (CRUD) operations.

### Defining Policies

**File**: `app/Policies/ProductPolicy.php`

```php
namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Everyone can view products
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Only admins can create
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->is_admin;
    }

    /**
     * Only owner or admin can update
     */
    public function update(User $user, Product $product): bool
    {
        // Admin can update any
        if ($user->role === 'admin' || $user->is_admin) {
            return true;
        }

        // Owner can update own
        return $user->id === $product->user_id;
    }

    /**
     * Only owner or admin can delete
     */
    public function delete(User $user, Product $product): bool
    {
        // Admin can delete any
        if ($user->role === 'admin' || $user->is_admin) {
            return true;
        }

        // Owner can delete own
        return $user->id === $product->user_id;
    }
}
```

### Registering Policies

**File**: `app/Providers/AuthServiceProvider.php`

```php
protected $policies = [
    Product::class => ProductPolicy::class,
];
```

### Using Policies in Controllers

**Authorize Model Creation**

```php
public function create()
{
    // Check if user can create Product
    // Calls ProductPolicy::create(auth()->user())
    $this->authorize('create', Product::class);

    return view('products.create');
}

public function store(Request $request)
{
    $this->authorize('create', Product::class);

    Product::create($request->validated());

    return redirect('/products');
}
```

**Authorize Model Operations**

```php
public function edit(Product $product)
{
    // Check if user can update this specific product
    // Calls ProductPolicy::update(auth()->user(), $product)
    $this->authorize('update', $product);

    return view('products.edit', ['product' => $product]);
}

public function update(Request $request, Product $product)
{
    $this->authorize('update', $product);

    $product->update($request->validated());

    return redirect("/products/{$product->id}");
}

public function destroy(Product $product)
{
    // Check if user can delete this specific product
    // Calls ProductPolicy::delete(auth()->user(), $product)
    $this->authorize('delete', $product);

    $product->delete();

    return redirect('/products');
}
```

### Using Policies in Blade Templates

```blade
{{-- Check if user can update this product --}}
@can('update', $product)
    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
        Edit Product
    </a>
@endcan

{{-- Check if user can delete this product --}}
@can('delete', $product)
    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
@endcan

{{-- Check if user can create products --}}
@can('create', App\Models\Product::class)
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        Create Product
    </a>
@endcan

{{-- Opposite: @cannot --}}
@cannot('update', $product)
    <p>You cannot edit this product</p>
@endcannot
```

### Policy Methods

Standard policy methods:

```php
class ProductPolicy
{
    // View any products
    public function viewAny(?User $user): bool {}

    // View specific product
    public function view(?User $user, Product $product): bool {}

    // Create product
    public function create(User $user): bool {}

    // Update product
    public function update(User $user, Product $product): bool {}

    // Delete product
    public function delete(User $user, Product $product): bool {}

    // Restore deleted product
    public function restore(User $user, Product $product): bool {}

    // Permanently delete product
    public function forceDelete(User $user, Product $product): bool {}
}
```

---

## Part 3: Complete Example - Product Management

### 1. Authorization Flow

```php
// ProductPolicy.php
public function create(User $user): bool
{
    return $user->role === 'admin';
}

public function update(User $user, Product $product): bool
{
    if ($user->role === 'admin') return true;
    return $user->id === $product->user_id;
}

public function delete(User $user, Product $product): bool
{
    if ($user->role === 'admin') return true;
    return $user->id === $product->user_id;
}
```

### 2. Controller Usage

```php
class ProductController extends Controller
{
    public function create()
    {
        $this->authorize('create', Product::class);
        return view('products.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $product = Product::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('products.show', $product);
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return view('products.edit', ['product' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->validated());

        return redirect()->route('products.show', $product);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('products.index');
    }
}
```

### 3. Route Protection

```php
// routes/web.php

// Public routes - No authorization needed
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Protected routes - Requires authentication
Route::middleware('auth')->group(function () {
    // Create/Store - Policy checks if admin
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::post('/products', [ProductController::class, 'store']);

    // Edit/Update - Policy checks if admin or owner
    Route::get('/products/{product}/edit', [ProductController::class, 'edit']);
    Route::put('/products/{product}', [ProductController::class, 'update']);

    // Delete - Policy checks if admin or owner
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});
```

### 4. Blade Template Usage

```blade
<!-- products/index.blade.php -->

@forelse ($products as $product)
    <div class="product-card">
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->description }}</p>

        {{-- Everyone can view --}}
        <a href="{{ route('products.show', $product) }}">View</a>

        {{-- Only admin or owner can edit --}}
        @can('update', $product)
            <a href="{{ route('products.edit', $product) }}">Edit</a>
        @endcan

        {{-- Only admin or owner can delete --}}
        @can('delete', $product)
            <form action="{{ route('products.destroy', $product) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        @endcan
    </div>
@empty
    <p>No products available</p>
@endforelse

{{-- Only admins can create --}}
@can('create', App\Models\Product::class)
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        Create Product
    </a>
@endcan
```

---

## Part 4: Authorization Exceptions

### What Happens on Failed Authorization?

```php
// Controller
$this->authorize('update', $product); // Throws if not authorized

// Blade
@can('update', $product)
    {{-- Only shown if authorized --}}
@endcan
```

### Handling Authorization Exceptions

```php
// In controller or middleware
try {
    $this->authorize('admin');
} catch (AuthorizationException $e) {
    return response()->view('errors.403', [], 403);
}
```

### HTTP Status Codes

- **200** - Authorization successful, request proceeds
- **403** - Authorization failed (Forbidden)
- **401** - User not authenticated (Unauthorized)

---

## Part 5: Before Hooks

You can add a "before" hook to bypass checks for admins:

```php
class ProductPolicy
{
    /**
     * Perform pre-authorization checks
     */
    public function before(User $user, $ability)
    {
        // Admins can always do everything
        if ($user->role === 'admin') {
            return true;
        }
    }

    public function create(User $user): bool
    {
        // This is skipped for admins
        return false;
    }
}
```

---

## Part 6: Testing Authorization

### Unit Test Example

```php
use Tests\TestCase;

class ProductPolicyTest extends TestCase
{
    public function test_non_admin_cannot_create_product()
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->assertFalse(
            $this->app['auth']->user($user)
                ->can('create', Product::class)
        );
    }

    public function test_admin_can_create_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue(
            $this->app['auth']->user($admin)
                ->can('create', Product::class)
        );
    }

    public function test_owner_can_update_own_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $this->assertTrue(
            $this->app['auth']->user($user)
                ->can('update', $product)
        );
    }

    public function test_non_owner_cannot_update_product()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user1->id]);

        $this->assertFalse(
            $this->app['auth']->user($user2)
                ->can('update', $product)
        );
    }
}
```

### Feature Test Example

```php
public function test_admin_can_view_create_form()
{
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get('/products/create')
        ->assertOk();
}

public function test_non_admin_cannot_view_create_form()
{
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->get('/products/create')
        ->assertForbidden();
}
```

---

## Part 7: Files Created/Modified

1. ✅ `app/Providers/AuthServiceProvider.php` - NEW - Gates & policy registration
2. ✅ `app/Policies/ProductPolicy.php` - NEW - Product authorization rules
3. ✅ `app/Models/Product.php` - NEW - Product model
4. ✅ `app/Http/Controllers/ProductController.php` - NEW - Policy usage examples
5. ✅ `resources/views/products/index.blade.php` - NEW - Gate/Policy in templates
6. ✅ `resources/views/products/create.blade.php` - NEW - Create form
7. ✅ `resources/views/products/edit.blade.php` - NEW - Edit form
8. ✅ `resources/views/products/show.blade.php` - NEW - Product detail
9. ✅ `routes/web.php` - UPDATED - Product routes with examples
10. ✅ `database/migrations/2026_04_17_000100_create_products_table.php` - NEW

---

## Part 8: Quick Reference

### Gates vs Policies

| Feature      | Gates                   | Policies                                   |
| ------------ | ----------------------- | ------------------------------------------ |
| **Best for** | Global permissions      | Model-specific                             |
| **Usage**    | `Gate::allows('admin')` | `$this->authorize('create', Model::class)` |
| **Blade**    | `@can('admin')`         | `@can('update', $model)`                   |
| **Example**  | Admin checks            | Post ownership                             |

### Authorization Methods

```php
// Controllers
$this->authorize('action', Model::class);          // Model class
$this->authorize('action', $model);                // Model instance
Gate::allows('gate-name');                         // Gate
Gate::denies('gate-name');                         // Negated gate
auth()->user()->can('action', $model);             // User method

// Blade
@can('action', $model)                             // Model
@can('gate-name')                                  // Gate
@cannot('action', $model)                          // Negated
Gate::allows('gate-name') / Gate::denies(...)      // Direct gate
```

---

## Next Steps

1. Run migration: `php artisan migrate`
2. Create test users with different roles
3. Test authorization in browser
4. Review ProductPolicy for your business logic
5. Create additional policies as needed
