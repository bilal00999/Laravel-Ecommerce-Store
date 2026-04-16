# Quick Reference: Gates and Policies Implementation

## Summary

This Laravel app now includes complete role-based access control (RBAC) using Gates and Policies.

---

## Key Files

### 1. **AuthServiceProvider** - Gate & Policy Registration

```
app/Providers/AuthServiceProvider.php
```

Defines custom gates and registers policies:

- **Gates**: `admin`, `moderator`, `manage-users`, `manage-settings`, `view-analytics`
- **Policies**: `Product` model → `ProductPolicy`

### 2. **ProductPolicy** - Model Authorization Rules

```
app/Policies/ProductPolicy.php
```

Methods:

- `viewAny()` - Everyone can view
- `view()` - Everyone can view specific product
- `create()` - Only admins
- `update()` - Admin or product owner
- `delete()` - Admin or product owner
- `restore()`, `forceDelete()` - Admin only

### 3. **Product Model** - Product Database Model

```
app/Models/Product.php
```

Relationships:

- `belongsTo()` - Created by user
- Fillable: name, slug, description, price, stock, user_id

### 4. **ProductController** - Controller Examples

```
app/Http/Controllers/ProductController.php
```

Shows three ways to use authorization:

- **Policies in controller**: `$this->authorize('action', $model)`
- **Gate check via controller**: `Gate::allows('gate-name')`
- **Exception handling**: `Gate::authorize()` throws exceptions

### 5. **Routes** - Protected Endpoints

```
routes/web.php
```

Product routes with authorization:

- `GET /products` - Public (view all)
- `GET /products/{id}` - Public (view one)
- `GET/POST /products/create` - Auth + Policy (admin only)
- `GET/PUT /products/{id}/edit` - Auth + Policy (admin or owner)
- `DELETE /products/{id}` - Auth + Policy (admin or owner)

### 6. **Blade Templates** - UI Authorization

```
resources/views/products/
  ├── index.blade.php    - Show/hide create button, edit/delete links
  ├── create.blade.php   - Create form (admin only)
  ├── edit.blade.php     - Edit form (admin or owner)
  └── show.blade.php     - Product detail + admin panel
```

### 7. **Migration** - Products Table

```
database/migrations/2026_04_17_000100_create_products_table.php
```

Columns:

- id, user_id (creator), name, slug, description, price, stock, timestamps

---

## Usage Patterns

### Pattern 1: Gate in Controller

```php
// Check global permission
if (Gate::allows('admin')) {
    // Show admin content
}

// Throw exception if denied
Gate::authorize('manage-users');
```

### Pattern 2: Policy in Controller

```php
// Check model creation permission
$this->authorize('create', Product::class);

// Check model instance permission
$this->authorize('update', $product);
$this->authorize('delete', $product);
```

### Pattern 3: Gate in Blade

```blade
@can('admin')
    <p>Admin content</p>
@endcan

@unless (Gate::allows('admin'))
    <p>Not admin</p>
@endunless
```

### Pattern 4: Policy in Blade

```blade
@can('create', App\Models\Product::class)
    <a href="/products/create">Create</a>
@endcan

@can('update', $product)
    <a href="/products/{{ $product->id }}/edit">Edit</a>
@endcan

@can('delete', $product)
    <form method="POST" action="/products/{{ $product->id }}">
        @method('DELETE')
        <button>Delete</button>
    </form>
@endcan
```

### Pattern 5: Unauthenticated User Check

```php
// Gate with nullable user parameter
public function view(?User $user, Product $product): bool
{
    return true; // Anyone can view, even guests
}

// Policy allows null user
@can('view', $product)
    {{-- Even guests can see this --}}
@endcan
```

---

## Role Hierarchy

### User Roles

- **user** (default) - Regular user
- **moderator** - Can view analytics
- **admin** - Full access

### Gate Access by Role

| Gate            | User | Moderator | Admin |
| --------------- | ---- | --------- | ----- |
| admin           | ❌   | ❌        | ✅    |
| moderator       | ❌   | ✅        | ✅    |
| manage-users    | ❌   | ❌        | ✅    |
| manage-settings | ❌   | ❌        | ✅    |
| view-analytics  | ❌   | ✅        | ✅    |

### Product Policy by Role

| Action   | Owner | Admin | Other User |
| -------- | ----- | ----- | ---------- |
| View Any | ✅    | ✅    | ✅         |
| View One | ✅    | ✅    | ✅         |
| Create   | ❌    | ✅    | ❌         |
| Update   | ✅    | ✅    | ❌         |
| Delete   | ✅    | ✅    | ❌         |

---

## Testing Users

Create test users in tinker:

```bash
php artisan tinker
```

```php
// Regular user
$user = User::create([
    'name' => 'John Doe',
    'email' => 'user@example.com',
    'password' => Hash::make('password'),
    'role' => 'user',
]);

// Moderator
$mod = User::create([
    'name' => 'Jane Mod',
    'email' => 'moderator@example.com',
    'password' => Hash::make('password'),
    'role' => 'moderator',
]);

// Admin
$admin = User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'admin',
    'is_admin' => true,
]);

// Create sample products
$product1 = Product::create([
    'user_id' => $user->id,
    'name' => 'Product 1',
    'slug' => 'product-1',
    'description' => 'Description 1',
    'price' => 29.99,
    'stock' => 100,
]);

$product2 = Product::create([
    'user_id' => $admin->id,
    'name' => 'Admin Product',
    'slug' => 'admin-product',
    'description' => 'Admin created',
    'price' => 49.99,
    'stock' => 50,
]);
```

---

## Testing in Browser

### As Regular User (user@example.com)

- ✅ Can view `/products` (all products)
- ✅ Can view `/products/{id}` (specific product)
- ✅ Cannot access `/products/create` (redirected)
- ✅ Can edit only own products
- ✅ Can delete only own products
- ❌ Cannot access `/admin` routes

### As Moderator (moderator@example.com)

- ✅ Can view all products
- ❌ Cannot create products
- ❌ Cannot access admin routes
- ✅ Can see analytics (if implemented)

### As Admin (admin@example.com)

- ✅ Can view all products
- ✅ Can create products
- ✅ Can edit all products
- ✅ Can delete all products
- ✅ Can access admin routes
- ✅ Can see admin panel on product detail pages

---

## Common Errors & Solutions

### Error: `AuthorizationException`

```
Expected: User denied access to this action
Solution: User doesn't have required role or ownership
```

### Error: Policy method not found

```
Expected: Call to undefined method
Solution: Policy method name must match action name (create, update, delete, etc.)
```

### Error: @can directive not working

```
Expected: Blade code still shows or hides incorrectly
Solution:
  1. Verify user is authenticated
  2. Check policy/gate logic
  3. Clear view cache: php artisan view:clear
```

---

## Migration Checklist

After setup:

- ✅ Run `php artisan migrate` (products table created)
- ✅ AuthServiceProvider auto-discovered (Laravel 11)
- ✅ ProductPolicy registered in AuthServiceProvider
- ✅ ProductController uses authorize() calls
- ✅ Routes include example endpoints
- ✅ Blade templates show @can/@cannot usage

---

## Architecture Diagram

```
User (role: admin/moderator/user)
    ↓
Request to Route
    ↓
Middleware (auth, verified, admin)
    ↓
Controller Method
    ↓
$this->authorize() → Checks Policy or Gate
    ↓
    ├─ Policy Check (e.g., ProductPolicy::update)
    │   → Returns true/false based on ownership & role
    │
    ├─ Gate Check (e.g., Gate::allows('admin'))
    │   → Returns true/false based on role
    │
    └─ Success → Continue to handler
      Failure → Throw AuthorizationException (403)
    ↓
Return Response (JSON or Redirect)
    ↓
Blade Template uses @can/@cannot
    ↓
Show/Hide UI Elements based on authorization
```

---

## Next Steps

1. **Test all user roles** - Create test accounts and verify permissions
2. **Create more policies** - UserPolicy, OrderPolicy, etc.
3. **Add role seeder** - `php artisan make:seeder RoleSeeder`
4. **Implement audit logging** - Track who accessed what
5. **Add admin panel** - Full management interface for admins
6. **Create permission matrix** - Document all role permissions

---

## Reference Links

- Controller: [app/Http/Controllers/ProductController.php](app/Http/Controllers/ProductController.php)
- Policy: [app/Policies/ProductPolicy.php](app/Policies/ProductPolicy.php)
- Provider: [app/Providers/AuthServiceProvider.php](app/Providers/AuthServiceProvider.php)
- Comprehensive Guide: [GATES_AND_POLICIES_GUIDE.md](GATES_AND_POLICIES_GUIDE.md)
- Middleware Guide: [MIDDLEWARE_GUIDE.md](MIDDLEWARE_GUIDE.md)
