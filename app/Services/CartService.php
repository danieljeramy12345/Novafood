<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Food;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public const DEFAULT_DELIVERY_FEE = 50.00;
    public const FREE_DELIVERY_THRESHOLD = 800.00;

    /**
     * Get or create the current cart for the authenticated user or guest session.
     */
    public function getCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = Session::getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * Add a food item to the cart after validating availability and stock.
     */
    public function addItem(int $foodId, int $quantity = 1): array
    {
        $food = Food::find($foodId);

        if (! $food) {
            return ['success' => false, 'message' => 'Food item not found.'];
        }

        if (! $food->is_available) {
            return ['success' => false, 'message' => 'Sorry, ' . $food->name . ' is currently unavailable.'];
        }

        if ($quantity <= 0) {
            return ['success' => false, 'message' => 'Invalid quantity specified.'];
        }

        if ($food->stock_quantity < $quantity) {
            return ['success' => false, 'message' => "Only {$food->stock_quantity} items remaining in stock."];
        }

        $cart = $this->getCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('food_id', $food->id)
            ->first();

        $newQuantity = $cartItem ? $cartItem->quantity + $quantity : $quantity;

        if ($food->stock_quantity < $newQuantity) {
            return ['success' => false, 'message' => "Cannot add more. Maximum available stock is {$food->stock_quantity}."];
        }

        if ($cartItem) {
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'food_id' => $food->id,
                'quantity' => $newQuantity,
            ]);
        }

        return ['success' => true, 'message' => "{$food->name} added to your cart."];
    }

    /**
     * Update an item's quantity in the cart.
     */
    public function updateQuantity(int $cartItemId, int $quantity): array
    {
        $cart = $this->getCart();
        $item = CartItem::where('cart_id', $cart->id)->where('id', $cartItemId)->first();

        if (! $item) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        if ($quantity <= 0) {
            $item->delete();
            return ['success' => true, 'message' => 'Item removed from cart.'];
        }

        $food = $item->food;
        if (! $food || ! $food->is_available) {
            $item->delete();
            return ['success' => false, 'message' => 'This product is no longer available and was removed.'];
        }

        if ($food->stock_quantity < $quantity) {
            return ['success' => false, 'message' => "Only {$food->stock_quantity} items available in stock."];
        }

        $item->update(['quantity' => $quantity]);
        return ['success' => true, 'message' => 'Cart quantity updated.'];
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(int $cartItemId): array
    {
        $cart = $this->getCart();
        $item = CartItem::where('cart_id', $cart->id)->where('id', $cartItemId)->first();

        if ($item) {
            $item->delete();
            return ['success' => true, 'message' => 'Item removed from cart.'];
        }

        return ['success' => false, 'message' => 'Item not found in cart.'];
    }

    /**
     * Clear all items from the cart.
     */
    public function clearCart(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
    }

    /**
     * Calculate server-validated financial totals using current database prices.
     */
    public function calculateTotals(): array
    {
        $cart = $this->getCart();
        $items = $cart->items()->with('food')->get();

        $subtotal = 0.0;
        $validItems = [];

        foreach ($items as $item) {
            if ($item->food && $item->food->is_available) {
                $lineTotal = (float) $item->food->price * $item->quantity;
                $subtotal += $lineTotal;
                $validItems[] = [
                    'item' => $item,
                    'unit_price' => (float) $item->food->price,
                    'line_total' => $lineTotal,
                ];
            }
        }

        $deliveryFee = ($subtotal > 0 && $subtotal >= self::FREE_DELIVERY_THRESHOLD) ? 0.0 : ($subtotal > 0 ? self::DEFAULT_DELIVERY_FEE : 0.0);
        $grandTotal = $subtotal + $deliveryFee;

        return [
            'cart' => $cart,
            'items' => $items,
            'valid_items' => $validItems,
            'subtotal' => round($subtotal, 2),
            'delivery_fee' => round($deliveryFee, 2),
            'grand_total' => round($grandTotal, 2),
            'item_count' => (int) $items->sum('quantity'),
        ];
    }

    /**
     * Merge guest session cart into user cart after login.
     */
    public function mergeGuestCartToUser(User $user): void
    {
        $sessionId = Session::getId();
        $guestCart = Cart::where('session_id', $sessionId)->first();

        if (! $guestCart || $guestCart->items()->count() === 0) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($guestCart->items as $guestItem) {
            $existing = CartItem::where('cart_id', $userCart->id)
                ->where('food_id', $guestItem->food_id)
                ->first();

            if ($existing) {
                $existing->update([
                    'quantity' => $existing->quantity + $guestItem->quantity,
                ]);
            } else {
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
    }
}
