<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ==========================================
     * INDEX - Everyone can view all products
     * ==========================================
     */

    public function test_guest_can_view_products_index()
    {
        // Create some products
        Product::factory(3)->create();

        $response = $this->get('/products');

        $response->assertOk();
        $response->assertviewHas('products');
    }

    public function test_authenticated_user_can_view_products_index()
    {
        $user = User::factory()->create();
        Product::factory(3)->create();

        $response = $this->actingAs($user)->get('/products');

        $response->assertOk();
        $response->assertViewHas('products');
    }

    /**
     * ==========================================
     * SHOW - Everyone can view specific product
     * ==========================================
     */

    public function test_guest_can_view_product_detail()
    {
        $product = Product::factory()->create();

        $response = $this->get("/products/{$product->id}");

        $response->assertOk();
        $response->assertViewHas('product');
    }

    public function test_authenticated_user_can_view_product_detail()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->get("/products/{$product->id}");

        $response->assertOk();
        $response->assertViewHas('product');
    }

    /**
     * ==========================================
     * CREATE - Only admins can access create form
     * ==========================================
     */

    public function test_guest_cannot_view_create_form()
    {
        $response = $this->get('/products/create');

        // Should redirect to login
        $response->assertRedirect('/login');
    }

    public function test_regular_user_cannot_view_create_form()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/products/create');

        // Should return 403 Forbidden
        $response->assertForbidden();
    }

    public function test_admin_can_view_create_form()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/products/create');

        $response->assertOk();
        $response->assertViewIs('products.create');
    }

    /**
     * ==========================================
     * STORE - Only admins can create products
     * ==========================================
     */

    public function test_guest_cannot_store_product()
    {
        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 29.99,
            'stock' => 100,
        ];

        $response = $this->post('/products', $data);

        $response->assertRedirect('/login');
    }

    public function test_regular_user_cannot_store_product()
    {
        $user = User::factory()->create(['role' => 'user']);

        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 29.99,
            'stock' => 100,
        ];

        $response = $this->actingAs($user)->post('/products', $data);

        $response->assertForbidden();
    }

    public function test_admin_can_store_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $data = [
            'name' => 'New Product',
            'description' => 'Product Description',
            'price' => 49.99,
            'stock' => 50,
        ];

        $response = $this->actingAs($admin)->post('/products', $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['name' => 'New Product']);
    }

    /**
     * ==========================================
     * EDIT - Admin or owner can edit
     * ==========================================
     */

    public function test_guest_cannot_view_edit_form()
    {
        $product = Product::factory()->create();

        $response = $this->get("/products/{$product->id}/edit");

        $response->assertRedirect('/login');
    }

    public function test_non_owner_cannot_view_edit_form()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser)
            ->get("/products/{$product->id}/edit");

        $response->assertForbidden();
    }

    public function test_owner_can_view_edit_form()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get("/products/{$product->id}/edit");

        $response->assertOk();
        $response->assertViewHas('product', $product);
    }

    public function test_admin_can_view_edit_form_of_any_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)
            ->get("/products/{$product->id}/edit");

        $response->assertOk();
        $response->assertViewHas('product', $product);
    }

    /**
     * ==========================================
     * UPDATE - Admin or owner can update
     * ==========================================
     */

    public function test_guest_cannot_update_product()
    {
        $product = Product::factory()->create();

        $data = ['name' => 'Updated Name'];

        $response = $this->put("/products/{$product->id}", $data);

        $response->assertRedirect('/login');
    }

    public function test_non_owner_cannot_update_product()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $owner->id]);

        $data = ['name' => 'Updated Name'];

        $response = $this->actingAs($otherUser)
            ->put("/products/{$product->id}", $data);

        $response->assertForbidden();
    }

    public function test_owner_can_update_own_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'name' => 'Original Name',
        ]);

        $data = [
            'name' => 'Updated Name',
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
        ];

        $response = $this->actingAs($user)
            ->put("/products/{$product->id}", $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_admin_can_update_any_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['name' => 'Original Name']);

        $data = [
            'name' => 'Admin Updated Name',
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
        ];

        $response = $this->actingAs($admin)
            ->put("/products/{$product->id}", $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Admin Updated Name',
        ]);
    }

    /**
     * ==========================================
     * DELETE - Admin or owner can delete
     * ==========================================
     */

    public function test_guest_cannot_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->delete("/products/{$product->id}");

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_non_owner_cannot_delete_product()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser)
            ->delete("/products/{$product->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_owner_can_delete_own_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->delete("/products/{$product->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_delete_any_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)
            ->delete("/products/{$product->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /**
     * ==========================================
     * VALIDATION - Test data validation
     * ==========================================
     */

    public function test_create_product_requires_name()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $data = [
            'name' => '', // Missing
            'description' => 'Test',
            'price' => 29.99,
            'stock' => 100,
        ];

        $response = $this->actingAs($admin)->post('/products', $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_create_product_requires_valid_price()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $data = [
            'name' => 'Product',
            'description' => 'Test',
            'price' => -10, // Invalid
            'stock' => 100,
        ];

        $response = $this->actingAs($admin)->post('/products', $data);

        $response->assertSessionHasErrors('price');
    }

    public function test_product_creator_is_current_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $data = [
            'name' => 'Test Product',
            'description' => 'Description',
            'price' => 29.99,
            'stock' => 100,
        ];

        $this->actingAs($admin)->post('/products', $data);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'user_id' => $admin->id,
        ]);
    }
}
