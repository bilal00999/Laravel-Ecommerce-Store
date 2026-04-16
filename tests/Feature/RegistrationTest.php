<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registration form is accessible
     */
    public function test_registration_page_is_accessible()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * Test user can register with valid data
     */
    public function test_user_can_register()
    {
        Event::fake();

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'agree' => true,
        ]);

        // User should be created
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);

        // User should be authenticated
        $this->assertAuthenticated();

        // Should be redirected to email verification
        $response->assertRedirect('/email/verify');

        // Registered event should be fired
        Event::assertDispatched(Registered::class);
    }

    /**
     * Test registration fails with duplicate email
     */
    public function test_registration_fails_with_duplicate_email()
    {
        User::factory()->create(['email' => 'john@example.com']);

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test registration fails with mismatched passwords
     */
    public function test_registration_fails_with_mismatched_passwords()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'DifferentPassword@123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'john@example.com']);
    }

    /**
     * Test registration fails with weak password
     */
    public function test_registration_fails_with_weak_password()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'weakpass',  // Missing uppercase, number, special char
            'password_confirmation' => 'weakpass',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'john@example.com']);
    }

    /**
     * Test registration fails with invalid email
     */
    public function test_registration_fails_with_invalid_email()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test registration fails with missing name
     */
    public function test_registration_fails_with_missing_name()
    {
        $response = $this->post('/register', [
            'email' => 'john@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test password is hashed
     */
    public function test_password_is_hashed()
    {
        $password = 'Password@123';

        $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotEquals($password, $user->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check($password, $user->password));
    }

    /**
     * Test API registration with valid data
     */
    public function test_api_registration_with_valid_data()
    {
        Event::fake();

        $response = $this->postJson('/api/register', [
            'name' => 'API User',
            'email' => 'api@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'user' => ['id', 'name', 'email', 'email_verified_at'],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'api@example.com',
            'name' => 'API User',
        ]);

        Event::assertDispatched(Registered::class);
    }

    /**
     * Test API registration fails with duplicate email
     */
    public function test_api_registration_fails_with_duplicate_email()
    {
        User::factory()->create(['email' => 'api@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'API User',
            'email' => 'api@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    /**
     * Test is_admin is false for new users
     */
    public function test_new_user_is_not_admin()
    {
        $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertFalse($user->is_admin);
    }

    /**
     * Test email is verified when user clicks link
     */
    public function test_email_verification_notice_shows()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/email/verify');
        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-email');
    }

    /**
     * Test user cannot login without email verification
     */
    public function test_unverified_user_redirected_to_verification()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        
        // Should redirect because dashboard requires 'verified' middleware
        $response->assertRedirect('/email/verify');
    }
}
