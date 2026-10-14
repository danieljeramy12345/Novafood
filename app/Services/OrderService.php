<?php

namespace App\Services;

use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Create an order from current cart within a safe database transaction.
     *
     * @throws Exception
     */
    public function createOrder(User $user, array $customerData): Order
    {
        return DB::transaction(function () use ($user, $customerData) {
            $cartData = $this->cartService->calculateTotals();
            $items = $cartData['items'];

            if ($items->isEmpty()) {
                throw new Exception('Your shopping cart is empty.');
            }

            // Lock food rows for update to prevent race conditions during inventory decrement
            $foodIds = $items->pluck('food_id')->toArray();
            $foods = Food::whereIn('id', $foodIds)->lockForUpdate()->get()->keyBy('id');

            $subtotal = 0.0;
            $orderItemsData = [];

            foreach ($items as $item) {
                /** @var Food|null $food */
                $food = $foods->get($item->food_id);

                if (! $food) {
                    throw new Exception("Product ID {$item->food_id} no longer exists.");
                }

                if (! $food->is_available) {
                    throw new Exception("Sorry, \"{$food->name}\" is currently unavailable.");
                }

                if ($food->stock_quantity < $item->quantity) {
                    throw new Exception("Insufficient stock for \"{$food->name}\". Only {$food->stock_quantity} left.");
                }

                $unitPrice = (float) $food->price;
                $lineSubtotal = round($unitPrice * $item->quantity, 2);
                $subtotal += $lineSubtotal;

                // Decrement stock atomically
                $food->decrement('stock_quantity', $item->quantity);

                $orderItemsData[] = [
                    'food_id' => $food->id,
                    'food_name' => $food->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $item->quantity,
                    'line_subtotal' => $lineSubtotal,
                ];
            }

            $deliveryFee = ($subtotal >= CartService::FREE_DELIVERY_THRESHOLD) ? 0.0 : CartService::DEFAULT_DELIVERY_FEE;
            $grandTotal = round($subtotal + $deliveryFee, 2);

            // Generate unique human-readable order number e.g. NF-20261014-XXXX
            $orderNumber = 'NF-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            while (Order::where('order_number', $orderNumber)->exists()) {
                $orderNumber = 'NF-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            }

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'customer_name' => $customerData['customer_name'],
                'customer_phone' => $customerData['customer_phone'],
                'delivery_address' => $customerData['delivery_address'],
                'notes' => $customerData['notes'] ?? null,
                'payment_method' => $customerData['payment_method'] ?? 'cod',
                'payment_status' => 'pending',
                'order_status' => Order::STATUS_PENDING,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'grand_total' => $grandTotal,
                'estimated_delivery_at' => now()->addMinutes(40),
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            // Create initial status history entry
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => null,
                'new_status' => Order::STATUS_PENDING,
                'changed_by' => $user->id,
                'note' => 'Order placed successfully by customer via Cash on Delivery (COD).',
            ]);

            // Clear the cart
            $this->cartService->clearCart();

            return $order;
        });
    }

    /**
     * Update order status with validation and history logging.
     *
     * @throws Exception
     */
    public function updateStatus(Order $order, string $newStatus, ?User $actor = null, ?string $note = null): Order
    {
        if (! $order->isValidStatusTransition($newStatus)) {
            throw new Exception("Invalid status change from '{$order->order_status}' to '{$newStatus}'.");
        }

        $oldStatus = $order->order_status;

        // If cancelling, restore inventory atomically
        if ($newStatus === Order::STATUS_CANCELLED && $oldStatus !== Order::STATUS_CANCELLED) {
            foreach ($order->items as $item) {
                if ($item->food_id) {
                    Food::where('id', $item->food_id)->increment('stock_quantity', $item->quantity);
                }
            }
        }

        // If delivered and payment was pending COD, update payment status to paid
        if ($newStatus === Order::STATUS_DELIVERED && $order->payment_method === 'cod') {
            $order->payment_status = 'paid';
        }

        $order->order_status = $newStatus;
        $order->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $actor?->id,
            'note' => $note ?: "Status updated to " . (Order::STATUSES[$newStatus] ?? $newStatus),
        ]);

        return $order;
    }
}
