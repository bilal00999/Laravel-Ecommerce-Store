<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test web login form is accessible
     */
    public function test_login_page_is_accessible()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test user can login via web (session-based)
     */
    public function test_user_can_login_web()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test user can login via API with JWT
     */
    public function test_user_can_login_api()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'token',
            'user' => ['id', 'name', 'email', 'is_admin'],
        ]);

        $this->assertNotNull($response['token']);
        $this->assertTrue($response['success']);
    }

    /**
     * Test login fails with invalid credentials
     */
    public function test_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
    }

    /**
     * Test API login fails with invalid credentials
     */
    public function test_api_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJsonFragment(['success' => false]);
    }

    /**
     * Test user can access protected routes after login
     */
    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }

    /**
     * Test unauthenticated user cannot access dashboard
     */
    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test JWT token is required for protected API routes
     */
    public function test_api_requires_valid_jwt_token()
    {
        $response = $this->getJson('/api/profile');

        $response->assertStatus(401);
    }

    /**
     * Test user can access API with valid JWT token
     */
    public function test_api_allows_access_with_valid_jwt_token()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/profile', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
    }

    /**
     * Test /api/me endpoint returns current user
     */
    public function test_api_me_endpoint_returns_current_user()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/me', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    /**
     * Test token refresh endpoint
     */
    public function test_token_refresh()
    {
        $user = User::factory()->create();
        $oldToken = JWTAuth::fromUser($user);

        $response = $this->postJson('/api/refresh', [], [
            'Authorization' => "Bearer $oldToken",
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'token']);
        $this->assertNotNull($response['token']);
    }

    /**
     * Test token validation endpoint
     */
    public function test_token_validation()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->postJson('/api/validate-token', [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
    }

    /**
     * Test user can logout from web
     */
    public function test_user_can_logout_web()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }

    /**
     * Test user can logout from API
     */
    public function test_user_can_logout_api()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
    }

    /**
     * Test token is blacklisted after logout
     */
    public function test_token_is_blacklisted_after_logout()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // Logout
        $this->postJson('/api/logout', [], [
            'Authorization' => "Bearer $token",
        ]);

        // Try to use the same token - should fail
        $response = $this->getJson('/api/profile', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test admin user can see admin panel
     */
    public function test_admin_user_can_see_admin_panel()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['is_admin' => false]);

        // Admin can see dashboard
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSeeText('Admin Panel');

        // Regular user cannot
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertDontSeeText('Admin Panel');
    }

    /**
     * Test admin can access admin API endpoints
     */
    public function test_admin_can_access_admin_api_endpoints()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $token = JWTAuth::fromUser($admin);

        $response = $this->getJson('/api/admin/dashboard', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test non-admin cannot access admin API endpoints
     */
    public function test_non_admin_cannot_access_admin_api_endpoints()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/admin/dashboard', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(403);
    }
}
