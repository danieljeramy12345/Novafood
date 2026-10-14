<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Food;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Master Admin',
            'email' => 'masteradmin@novafood.com',
            'password' => Hash::make('AdminPass123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Pizza',
            'slug' => 'pizza',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_and_update_food_product(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.foods.store'), [
            'category_id' => $this->category->id,
            'name' => 'Hawaiian Supreme',
            'description' => 'Sweet pineapple and ham on mozzarella.',
            'price' => 269.00,
            'stock_quantity' => 30,
            'is_available' => 1,
            'is_featured' => 1,
        ]);

        $response->assertRedirect(route('admin.foods.index'));
        $this->assertDatabaseHas('foods', [
            'name' => 'Hawaiian Supreme',
            'price' => 269.00,
            'stock_quantity' => 30,
        ]);

        $food = Food::where('name', 'Hawaiian Supreme')->first();

        // Update food
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.foods.update', $food), [
            'category_id' => $this->category->id,
            'name' => 'Hawaiian Supreme XL',
            'description' => 'Extra large size with double cheese.',
            'price' => 329.00,
            'stock_quantity' => 45,
            'is_available' => 1,
        ]);

        $updateResponse->assertRedirect(route('admin.foods.index'));
        $this->assertDatabaseHas('foods', [
            'id' => $food->id,
            'name' => 'Hawaiian Supreme XL',
            'price' => 329.00,
        ]);
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Sandwiches',
            'icon' => '🥪',
            'description' => 'Toasted club sandwiches.',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Sandwiches',
            'slug' => 'sandwiches',
        ]);
    }

    public function test_invalid_order_status_transitions_are_rejected(): void
    {
        $customer = User::create([
            'name' => 'Test Customer',
            'email' => 'tc@test.com',
            'password' => Hash::make('Password123!'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'NF-20261014-1111',
            'user_id' => $customer->id,
            'customer_name' => 'Test Customer',
            'customer_phone' => '+63 900 000 0000',
            'delivery_address' => 'Some address',
            'subtotal' => 100.00,
            'delivery_fee' => 50.00,
            'grand_total' => 150.00,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'order_status' => Order::STATUS_PENDING,
        ]);

        // Attempt invalid jump from 'pending' directly to 'delivered'
        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'order_status' => Order::STATUS_DELIVERED,
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(Order::STATUS_PENDING, $order->fresh()->order_status);

        // Attempt valid transition from 'pending' to 'confirmed'
        $validResponse = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'order_status' => Order::STATUS_CONFIRMED,
        ]);

        $validResponse->assertSessionHas('success');
        $this->assertEquals(Order::STATUS_CONFIRMED, $order->fresh()->order_status);
    }
}
