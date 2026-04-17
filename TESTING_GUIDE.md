# Testing Guide: Gates and Policies

## Overview

This guide shows you how to test the authorization system (Gates and Policies) in your Laravel app.

Three test files have been created:

1. **`tests/Unit/GatesTest.php`** - Test Gates directly
2. **`tests/Unit/ProductPolicyTest.php`** - Test Policies directly
3. **`tests/Feature/ProductAuthorizationTest.php`** - Test HTTP endpoints and authorization

---

## Quick Start

### Run All Tests

```bash
php artisan test
```

### Run Specific Test File

```bash
php artisan test tests/Unit/GatesTest.php
php artisan test tests/Unit/ProductPolicyTest.php
php artisan test tests/Feature/ProductAuthorizationTest.php
```

### Run Single Test Method

```bash
php artisan test --filter=test_admin_can_create_product
php artisan test --filter=test_non_owner_cannot_update_product
```

### Run with Verbose Output

```bash
php artisan test --verbose
```

### Run Tests and Show Time Taken

```bash
php artisan test --stop-on-failure
```

---

## Test 1: Unit - Gates (GatesTest.php)

Tests the authorization gates defined in `AuthServiceProvider`.

### What It Tests

```php
// Gate: Admin
test_admin_gate_denies_regular_user()          // Regular users can't pass
test_admin_gate_allows_admin_user()            // Admins can pass
test_admin_gate_allows_is_admin_flag()         // is_admin flag works

// Gate: Moderator
test_moderator_gate_denies_regular_user()      // Regular users denied
test_moderator_gate_allows_moderator()         // Moderators allowed
test_moderator_gate_allows_admin()             // Admins also allowed

// Gate: Manage-Users
test_manage_users_gate_denies_non_admin()      // Only admins
test_manage_users_gate_allows_admin()          // Admin passes

// Gate: Manage-Settings
test_manage_settings_gate_denies_non_admin()   // Only admins
test_manage_settings_gate_allows_admin()       // Admin passes

// Gate: View-Analytics
test_view_analytics_gate_denies_regular_user() // Regular denied
test_view_analytics_gate_allows_moderator()    // Moderator passes
test_view_analytics_gate_allows_admin()        // Admin passes

// Comprehensive Role Testing
test_user_role_permissions()                   // User has no gates
test_moderator_role_permissions()              // Moderator has 2 gates
test_admin_role_permissions()                  // Admin has all gates
```

### Run Gates Tests

```bash
php artisan test tests/Unit/GatesTest.php
```

### Example Output

```
PASS  Tests\Unit\GatesTest
  ✓ admin gate denies regular user
  ✓ admin gate allows admin user
  ✓ admin gate allows is admin flag
  ✓ moderator gate denies regular user
  ✓ moderator gate allows moderator
  ✓ moderator gate allows admin
  ✓ manage users gate denies non admin
  ✓ manage users gate allows admin
  ...

Tests:  17 passed
```

---

## Test 2: Unit - Policies (ProductPolicyTest.php)

Tests the ProductPolicy directly using `can()` method.

### What It Tests

```php
// CREATE Permission
test_non_admin_cannot_create_product()         // Only admins can create
test_admin_can_create_product()                // Admin can create
test_is_admin_flag_allows_create()             // Legacy flag works

// VIEW Permission
test_anyone_can_view_any_products()            // Guest can view
test_authenticated_user_can_view_any_products()// User can view

// UPDATE Permission
test_owner_can_update_own_product()            // Owner can update
test_non_owner_cannot_update_product()         // Non-owner blocked
test_admin_can_update_any_product()            // Admin can update any

// DELETE Permission
test_owner_can_delete_own_product()            // Owner can delete
test_non_owner_cannot_delete_product()         // Non-owner blocked
test_admin_can_delete_any_product()            // Admin can delete any

// RESTORE Permission
test_only_admin_can_restore_product()          // Only admin

// FORCE DELETE Permission
test_only_admin_can_force_delete()             // Only admin
```

### Run Policy Tests

```bash
php artisan test tests/Unit/ProductPolicyTest.php
```

### Example Output

```
PASS  Tests\Unit\ProductPolicyTest
  ✓ non admin cannot create product
  ✓ admin can create product
  ✓ is admin flag allows create
  ✓ anyone can view any products
  ✓ authenticated user can view any products
  ✓ owner can update own product
  ✓ non owner cannot update product
  ✓ admin can update any product
  ✓ owner can delete own product
  ✓ non owner cannot delete product
  ✓ admin can delete any product
  ✓ only admin can restore product
  ✓ only admin can force delete
  ...

Tests:  16 passed
```

---

## Test 3: Feature - HTTP Endpoints (ProductAuthorizationTest.php)

Tests actual HTTP requests and authorization at the route level.

### What It Tests

```php
// INDEX - View all products
test_guest_can_view_products_index()
test_authenticated_user_can_view_products_index()

// SHOW - View one product
test_guest_can_view_product_detail()
test_authenticated_user_can_view_product_detail()

// CREATE FORM - Access form
test_guest_cannot_view_create_form()           // Redirects to login
test_regular_user_cannot_view_create_form()    // Returns 403
test_admin_can_view_create_form()              // Returns 200

// STORE - Create product
test_guest_cannot_store_product()              // Redirects
test_regular_user_cannot_store_product()       // 403 Forbidden
test_admin_can_store_product()                 // Creates product

// EDIT FORM - Access form
test_guest_cannot_view_edit_form()             // Redirects
test_non_owner_cannot_view_edit_form()         // 403 Forbidden
test_owner_can_view_edit_form()                // Returns 200
test_admin_can_view_edit_form_of_any_product() // Returns 200

// UPDATE - Update product
test_guest_cannot_update_product()             // Redirects
test_non_owner_cannot_update_product()         // 403 Forbidden
test_owner_can_update_own_product()            // Updates
test_admin_can_update_any_product()            // Updates

// DELETE - Delete product
test_guest_cannot_delete_product()             // Redirects
test_non_owner_cannot_delete_product()         // 403 Forbidden
test_owner_can_delete_own_product()            // Deletes
test_admin_can_delete_any_product()            // Deletes

// VALIDATION - Data validation
test_create_product_requires_name()            // Validation error
test_create_product_requires_valid_price()     // Validation error
test_product_creator_is_current_user()         // Creator = current user
```

### Run Feature Tests

```bash
php artisan test tests/Feature/ProductAuthorizationTest.php
```

### Example Output

```
PASS  Tests\Feature\ProductAuthorizationTest
  ✓ guest can view products index
  ✓ authenticated user can view products index
  ✓ guest can view product detail
  ✓ authenticated user can view product detail
  ✓ guest cannot view create form
  ✓ regular user cannot view create form
  ✓ admin can view create form
  ✓ guest cannot store product
  ✓ regular user cannot store product
  ✓ admin can store product
  ✓ guest cannot view edit form
  ✓ non owner cannot view edit form
  ✓ owner can view edit form
  ✓ admin can view edit form of any product
  ✓ guest cannot update product
  ✓ non owner cannot update product
  ✓ owner can update own product
  ✓ admin can update any product
  ✓ guest cannot delete product
  ✓ non owner cannot delete product
  ✓ owner can delete own product
  ✓ admin can delete any product
  ✓ create product requires name
  ✓ create product requires valid price
  ✓ product creator is current user
  ...

Tests:  25 passed
```

---

## Common Testing Patterns

### Pattern 1: Acting As User

```php
$user = User::factory()->create(['role' => 'user']);

$this->actingAs($user);
// Now all requests are authenticated as $user

$response = $this->get('/products');
```

### Pattern 2: Testing Gate Allow/Deny

```php
public function test_admin_gate()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'user']);

    // Using Gate facade
    $this->actingAs($admin);
    $this->assertTrue(Gate::allows('admin'));

    // Using can() method
    $this->actingAs($user);
    $this->assertFalse(auth()->user()->can('admin'));
}
```

### Pattern 3: Testing Authorization Exceptions

```php
public function test_cannot_update_others_product()
{
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $product = Product::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($otherUser)
        ->put("/products/{$product->id}", []);

    // Should return 403 Forbidden
    $response->assertForbidden();
}
```

### Pattern 4: Testing Database Changes

```php
public function test_product_is_created()
{
    $admin = User::factory()->create(['role' => 'admin']);

    $data = ['name' => 'Test', 'description' => 'Desc', 'price' => 29.99, 'stock' => 100];

    $this->actingAs($admin)->post('/products', $data);

    // Check product exists in database
    $this->assertDatabaseHas('products', ['name' => 'Test']);

    // Check product doesn't exist
    $this->assertDatabaseMissing('products', ['id' => 999]);
}
```

### Pattern 5: Testing With RefreshDatabase

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductAuthorizationTest extends TestCase
{
    use RefreshDatabase; // Rollback database after each test

    public function test_something()
    {
        // Database is clean here
        $user = User::factory()->create();
        // Database is clean again after test
    }
}
```

---

## Test Response Methods

### Assertion Methods Available

```php
// Status Code
$response->assertOk();                    // 200
$response->assertForbidden();             // 403
$response->assertUnauthorized();          // 401
$response->assertNotFound();              // 404
$response->assertStatus(200);             // Specific code

// Redirects
$response->assertRedirect();              // Any redirect
$response->assertRedirect('/login');      // Specific redirect

// Views
$response->assertViewIs('products.create');      // Specific view
$response->assertViewHas('product', $product);   // View has variable
$response->assertViewHasErrors(['name']);        // Has validation errors

// Database
$this->assertDatabaseHas('products', ['name' => 'Test']);
$this->assertDatabaseMissing('products', ['id' => 999]);
$this->assertDatabaseCount('products', 5);

// JSON (for API)
$response->assertJsonStructure(['id', 'name']);
$response->assertJson(['status' => 'ok']);
```

---

## Running Specific Test Scenarios

### Test Only Authorization

```bash
# Run only policy tests
php artisan test tests/Unit/ProductPolicyTest.php

# Run only gate tests
php artisan test tests/Unit/GatesTest.php

# Run only HTTP tests
php artisan test tests/Feature/ProductAuthorizationTest.php
```

### Test Specific User Roles

```bash
# Test admin only
php artisan test --filter=admin

# Test owner only
php artisan test --filter=owner

# Test guest only
php artisan test --filter=guest
```

### Run Tests Until First Failure

```bash
php artisan test --stop-on-failure
```

### Run Tests with Coverage

```bash
php artisan test --coverage

# Or with detailed output
php artisan test --coverage --coverage-html coverage
```

---

## Debugging Tests

### Print Debug Info

```php
public function test_something()
{
    $user = User::factory()->create();

    // Print to console
    dd($user); // Dump and die

    // Or use dump to continue
    dump($user->role); // Prints but continues

    // Or use ray
    ray($user)->color('red');
}
```

### Run Test with Verbose Output

```bash
php artisan test tests/Unit/GatesTest.php --verbose
```

### Run Single Test Method Only

```bash
# Run one specific test
php artisan test tests/Unit/GatesTest.php --filter=test_admin_gate_allows_admin_user

# Run tests matching pattern
php artisan test --filter=admin
```

### Re-run Failed Tests

```bash
php artisan test --only-failures
```

---

## Test Checklist

Before considering authorization complete, verify:

- ✅ Unit tests pass (Gates, Policies)
- ✅ Feature tests pass (HTTP endpoints)
- ✅ Regular users cannot access admin routes
- ✅ Owners can edit/delete own resources
- ✅ Non-owners cannot edit/delete others' resources
- ✅ Admins can access everything
- ✅ Guests are redirected to login
- ✅ Validation works (required fields)
- ✅ Data integrity (products have correct user_id)
- ✅ Database properly rolls back between tests

---

## Test Files Summary

| File                           | Tests  | Purpose                              |
| ------------------------------ | ------ | ------------------------------------ |
| `GatesTest.php`                | 17     | Test gate authorization checks       |
| `ProductPolicyTest.php`        | 16     | Test policy authorization checks     |
| `ProductAuthorizationTest.php` | 25     | Test HTTP endpoints and full flow    |
| **Total**                      | **58** | Comprehensive authorization coverage |

---

## Running All Tests at Once

```bash
# Run everything
php artisan test

# With verbose output
php artisan test --verbose

# Stop on first failure
php artisan test --stop-on-failure

# Show test names as they run
php artisan test --debug

# Generate coverage report
php artisan test --coverage
```

---

## CI/CD Integration

For GitHub Actions or other CI/CD:

```yaml
- name: Run Tests
  run: php artisan test

- name: Generate Coverage
  run: php artisan test --coverage
```

---

## Fixing Common Test Errors

### Error: "Method handle does not exist"

```
Solution: Model not being used with factory
Fix: Use User::factory()->create() instead of new User()
```

### Error: "No query results found"

```
Solution: Missing RefreshDatabase trait
Fix: Add 'use RefreshDatabase;' to test class
```

### Error: "User not authenticated"

```
Solution: Not using actingAs()
Fix: $this->actingAs($user) before making requests
```

### Error: "Expected status 403 but got 401"

```
Solution: User not authenticated, being redirected to login
Fix: Make sure $this->actingAs($user) is called first
```

---

## Next Steps

1. **Run all tests**: `php artisan test`
2. **Verify they pass**: All 58 tests should pass
3. **Review test code**: Understand what each test does
4. **Add more tests**: For any new features
5. **Use in CI/CD**: Automate test runs on push
6. **Maintain**: Keep tests updated as code changes
