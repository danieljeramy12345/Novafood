<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_succeeds_with_valid_data(): void
    {
        $response = $this->post('/register', [
            'name' => 'Maria Clara',
            'email' => 'maria@example.com',
            'phone' => '+63 918 123 4567',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'address' => '456 Makati Avenue, Makati City',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'maria@example.com',
            'name' => 'Maria Clara',
            'role' => 'customer',
        ]);
    }

    public function test_registration_rejects_invalid_data(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => '',
            'email' => 'not-an-email',
            'phone' => '',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'phone', 'password']);
        $this->assertGuest();
    }

    public function test_registration_ignores_client_provided_admin_role(): void
    {
        $this->post('/register', [
            'name' => 'Sneaky User',
            'email' => 'sneaky@example.com',
            'phone' => '+63 900 000 0000',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'admin', // Attack attempt
        ]);

        $user = User::where('email', 'sneaky@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('customer', $user->role);
    }

    public function test_login_works_with_valid_credentials(): void
    {
        $user = User::create([
            'name' => 'Pedro Penduko',
            'email' => 'pedro@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'pedro@example.com',
            'password' => 'SecretPass123!',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::create([
            'name' => 'Pedro Penduko',
            'email' => 'pedro@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'pedro@example.com',
            'password' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout_successfully(): void
    {
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
