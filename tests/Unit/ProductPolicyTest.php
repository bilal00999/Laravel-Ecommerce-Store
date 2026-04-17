<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Only admins can create products
     */
    public function test_non_admin_cannot_create_product()
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user);
        $this->assertFalse(
            auth()->user()->can('create', Product::class)
        );
    }

    public function test_admin_can_create_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);
        $this->assertTrue(
            auth()->user()->can('create', Product::class)
        );
    }

    public function test_is_admin_flag_allows_create()
    {
        // Test legacy is_admin flag
        $user = User::factory()->create([
            'role' => 'user',
            'is_admin' => true, // Legacy flag
        ]);

        $this->actingAs($user);
        $this->assertTrue(
            auth()->user()->can('create', Product::class)
        );
    }

    /**
     * Test: Everyone can view products
     */
    public function test_anyone_can_view_any_products()
    {
        $this->assertTrue(
            auth()->guest()->can('viewAny', Product::class)
        );
    }

    public function test_authenticated_user_can_view_any_products()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->assertTrue(
            auth()->user()->can('viewAny', Product::class)
        );
    }

    /**
     * Test: Owner can update their own product
     */
    public function test_owner_can_update_own_product()
    {
        $owner = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner);
        $this->assertTrue(
            auth()->user()->can('update', $product)
        );
    }

    /**
     * Test: Non-owner cannot update other's product
     */
    public function test_non_owner_cannot_update_product()
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['user_id' => $user1->id]);

        $this->actingAs($user2);
        $this->assertFalse(
            auth()->user()->can('update', $product)
        );
    }

    /**
     * Test: Admin can update any product
     */
    public function test_admin_can_update_any_product()
    {
        $owner = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($admin);
        $this->assertTrue(
            auth()->user()->can('update', $product)
        );
    }

    /**
     * Test: Owner can delete their own product
     */
    public function test_owner_can_delete_own_product()
    {
        $owner = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner);
        $this->assertTrue(
            auth()->user()->can('delete', $product)
        );
    }

    /**
     * Test: Non-owner cannot delete other's product
     */
    public function test_non_owner_cannot_delete_product()
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['user_id' => $user1->id]);

        $this->actingAs($user2);
        $this->assertFalse(
            auth()->user()->can('delete', $product)
        );
    }

    /**
     * Test: Admin can delete any product
     */
    public function test_admin_can_delete_any_product()
    {
        $owner = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($admin);
        $this->assertTrue(
            auth()->user()->can('delete', $product)
        );
    }

    /**
     * Test: Only admin can restore product
     */
    public function test_only_admin_can_restore_product()
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $this->actingAs($user);
        $this->assertFalse(
            auth()->user()->can('restore', $product)
        );

        $this->actingAs($admin);
        $this->assertTrue(
            auth()->user()->can('restore', $product)
        );
    }

    /**
     * Test: Only admin can force delete
     */
    public function test_only_admin_can_force_delete()
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $this->actingAs($user);
        $this->assertFalse(
            auth()->user()->can('forceDelete', $product)
        );

        $this->actingAs($admin);
        $this->assertTrue(
            auth()->user()->can('forceDelete', $product)
        );
    }
}
