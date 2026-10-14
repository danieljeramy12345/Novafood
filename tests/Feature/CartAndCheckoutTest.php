<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Food;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CartAndCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected Food $food;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Burgers',
            'slug' => 'burgers',
            'is_active' => true,
        ]);

        $this->food = Food::create([
            'category_id' => $this->category->id,
            'name' => 'Cheeseburger Deluxe',
            'slug' => 'cheeseburger-deluxe',
            'description' => 'Delicious cheeseburger.',
            'price' => 129.00,
            'is_available' => true,
            'stock_quantity' => 20,
        ]);

        $this->customer = User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@test.com',
            'phone' => '+63 917 111 2222',
            'password' => Hash::make('Password123!'),
            'role' => 'customer',
            'address' => '123 Rizal Street, Manila',
            'is_active' => true,
        ]);
    }

    public function test_food_can_be_added_to_cart(): void
    {
        $response = $this->actingAs($this->customer)->post('/cart/add', [
            'food_id' => $this->food->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cart_items', [
            'food_id' => $this->food->id,
            'quantity' => 2,
        ]);
    }

    public function test_invalid_cart_quantities_are_rejected(): void
    {
        $response = $this->actingAs($this->customer)->post('/cart/add', [
            'food_id' => $this->food->id,
            'quantity' => -5,
        ]);

        $response->assertSessionHasErrors('quantity');
    }

    public function test_empty_cart_cannot_be_checked_out(): void
    {
        $response = $this->actingAs($this->customer)->get('/checkout');
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('warning');

        $processResponse = $this->actingAs($this->customer)->post('/checkout', [
            'customer_name' => 'Juan Dela Cruz',
            'customer_phone' => '+63 917 111 2222',
            'delivery_address' => '123 Rizal Street, Manila',
            'payment_method' => 'cod',
        ]);

        $processResponse->assertSessionHas('error');
    }

    public function test_checkout_creates_order_and_items_with_server_calculated_pricing(): void
    {
        // Add 2 items of food (₱129 * 2 = ₱258) + ₱50 delivery fee = ₱308
        $this->actingAs($this->customer)->post('/cart/add', [
            'food_id' => $this->food->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($this->customer)->post('/checkout', [
            'customer_name' => 'Juan Dela Cruz',
            'customer_phone' => '+63 917 111 2222',
            'delivery_address' => '123 Rizal Street, Manila',
            'notes' => 'Ring bell twice',
            'payment_method' => 'cod',
            'subtotal' => 1.00, // Client tries to spoof subtotal!
            'grand_total' => 1.00, // Client tries to spoof grand total!
        ]);

        $order = Order::where('user_id', $this->customer->id)->first();

        $this->assertNotNull($order);
        $response->assertRedirect(route('orders.show', $order));

        // Ensure server ignored client spoofed prices and calculated correct totals
        $this->assertEquals(258.00, (float) $order->subtotal);
        $this->assertEquals(50.00, (float) $order->delivery_fee);
        $this->assertEquals(308.00, (float) $order->grand_total);
        $this->assertEquals('pending', $order->order_status);
        $this->assertEquals('cod', $order->payment_method);

        // Check snapshot order items
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'food_id' => $this->food->id,
            'food_name' => 'Cheeseburger Deluxe',
            'unit_price' => 129.00,
            'quantity' => 2,
            'line_subtotal' => 258.00,
        ]);

        // Check inventory decrement (20 - 2 = 18)
        $this->assertEquals(18, $this->food->fresh()->stock_quantity);

        // Cart should be cleared after order
        $this->assertDatabaseMissing('cart_items', [
            'food_id' => $this->food->id,
        ]);
    }

    public function test_checkout_rejects_unavailable_products(): void
    {
        $this->food->update(['is_available' => false]);

        $this->actingAs($this->customer)->post('/cart/add', [
            'food_id' => $this->food->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->customer)->post('/checkout', [
            'customer_name' => 'Juan Dela Cruz',
            'customer_phone' => '+63 917 111 2222',
            'delivery_address' => '123 Rizal Street, Manila',
            'payment_method' => 'cod',
        ]);

        $response->assertSessionHas('error');
    }
}
