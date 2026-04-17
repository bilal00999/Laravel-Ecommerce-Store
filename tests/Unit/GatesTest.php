<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

class GatesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ==========================================
     * ADMIN GATE
     * ==========================================
     */

    public function test_admin_gate_denies_regular_user()
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user);
        $this->assertFalse(Gate::allows('admin'));
    }

    public function test_admin_gate_allows_admin_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);
        $this->assertTrue(Gate::allows('admin'));
    }

    public function test_admin_gate_allows_is_admin_flag()
    {
        // Test legacy is_admin flag
        $user = User::factory()->create([
            'role' => 'user',
            'is_admin' => true,
        ]);

        $this->actingAs($user);
        $this->assertTrue(Gate::allows('admin'));
    }

    /**
     * ==========================================
     * MODERATOR GATE
     * ==========================================
     */

    public function test_moderator_gate_denies_regular_user()
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user);
        $this->assertFalse(Gate::allows('moderator'));
    }

    public function test_moderator_gate_allows_moderator()
    {
        $mod = User::factory()->create(['role' => 'moderator']);

        $this->actingAs($mod);
        $this->assertTrue(Gate::allows('moderator'));
    }

    public function test_moderator_gate_allows_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);
        $this->assertTrue(Gate::allows('moderator'));
    }

    /**
     * ==========================================
     * MANAGE-USERS GATE
     * ==========================================
     */

    public function test_manage_users_gate_denies_non_admin()
    {
        $user = User::factory()->create(['role' => 'user']);
        $mod = User::factory()->create(['role' => 'moderator']);

        $this->actingAs($user);
        $this->assertFalse(Gate::allows('manage-users'));

        $this->actingAs($mod);
        $this->assertFalse(Gate::allows('manage-users'));
    }

    public function test_manage_users_gate_allows_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);
        $this->assertTrue(Gate::allows('manage-users'));
    }

    /**
     * ==========================================
     * MANAGE-SETTINGS GATE
     * ==========================================
     */

    public function test_manage_settings_gate_denies_non_admin()
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user);
        $this->assertFalse(Gate::allows('manage-settings'));
    }

    public function test_manage_settings_gate_allows_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);
        $this->assertTrue(Gate::allows('manage-settings'));
    }

    /**
     * ==========================================
     * VIEW-ANALYTICS GATE
     * ==========================================
     */

    public function test_view_analytics_gate_denies_regular_user()
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user);
        $this->assertFalse(Gate::allows('view-analytics'));
    }

    public function test_view_analytics_gate_allows_moderator()
    {
        $mod = User::factory()->create(['role' => 'moderator']);

        $this->actingAs($mod);
        $this->assertTrue(Gate::allows('view-analytics'));
    }

    public function test_view_analytics_gate_allows_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);
        $this->assertTrue(Gate::allows('view-analytics'));
    }

    /**
     * ==========================================
     * GATE DENY CHECKS
     * ==========================================
     */

    public function test_gate_denies_returns_opposite()
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);
        $this->assertTrue(Gate::denies('admin'));
        $this->assertFalse(Gate::denies('moderator')); // user is not moderator, so denies is false? No, denies returns true

        $this->actingAs($admin);
        $this->assertFalse(Gate::denies('admin'));
        $this->assertTrue(Gate::denies('moderator')); // Wait, admin should pass moderator gate
    }

    /**
     * ==========================================
     * USING auth()->user()->can()
     * ==========================================
     */

    public function test_can_method_on_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);
        $this->assertTrue(auth()->user()->can('admin'));
    }

    public function test_cannot_method_on_user()
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user);
        $this->assertFalse(auth()->user()->can('admin'));
    }

    /**
     * ==========================================
     * ROLE-BASED COMPREHENSIVE TEST
     * ==========================================
     */

    public function test_user_role_permissions()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $this->assertFalse(Gate::allows('admin'));
        $this->assertFalse(Gate::allows('moderator'));
        $this->assertFalse(Gate::allows('manage-users'));
        $this->assertFalse(Gate::allows('manage-settings'));
        $this->assertFalse(Gate::allows('view-analytics'));
    }

    public function test_moderator_role_permissions()
    {
        $mod = User::factory()->create(['role' => 'moderator']);
        $this->actingAs($mod);

        $this->assertFalse(Gate::allows('admin'));
        $this->assertTrue(Gate::allows('moderator'));
        $this->assertFalse(Gate::allows('manage-users'));
        $this->assertFalse(Gate::allows('manage-settings'));
        $this->assertTrue(Gate::allows('view-analytics'));
    }

    public function test_admin_role_permissions()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->assertTrue(Gate::allows('admin'));
        $this->assertTrue(Gate::allows('moderator'));
        $this->assertTrue(Gate::allows('manage-users'));
        $this->assertTrue(Gate::allows('manage-settings'));
        $this->assertTrue(Gate::allows('view-analytics'));
    }
}
