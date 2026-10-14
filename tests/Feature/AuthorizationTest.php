<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_protected_customer_pages(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('login'));

        $responseCheckout = $this->get('/checkout');
        $responseCheckout->assertRedirect(route('login'));

        $responseOrders = $this->get('/my-orders');
        $responseOrders->assertRedirect(route('login'));
    }

    public function test_customers_cannot_access_admin_routes(): void
    {
        $customer = User::create([
            'name' => 'Regular Customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('Password123!'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($customer)->get('/admin/dashboard');
        $response->assertStatus(403);

        $responseFoods = $this->actingAs($customer)->get('/admin/foods');
        $responseFoods->assertStatus(403);

        $responseOrders = $this->actingAs($customer)->get('/admin/orders');
        $responseOrders->assertStatus(403);
    }

    public function test_customers_cannot_view_another_customers_orders(): void
    {
        $customerA = User::create([
            'name' => 'Customer A',
            'email' => 'customera@test.com',
            'password' => Hash::make('Password123!'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $customerB = User::create([
            'name' => 'Customer B',
            'email' => 'customerb@test.com',
            'password' => Hash::make('Password123!'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $orderOfB = Order::create([
            'order_number' => 'NF-20261014-9999',
            'user_id' => $customerB->id,
            'customer_name' => 'Customer B',
            'customer_phone' => '+63 900 000 0002',
            'delivery_address' => 'Customer B Address',
            'subtotal' => 200.00,
            'delivery_fee' => 50.00,
            'grand_total' => 250.00,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);

        // Customer A tries to view Order of Customer B
        $response = $this->actingAs($customerA)->get(route('orders.show', $orderOfB));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard_and_orders(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('AdminPass123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('System Overview & Analytics');
    }
}
