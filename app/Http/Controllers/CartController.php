<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(): View
    {
        $cartData = $this->cartService->calculateTotals();

        return view('cart.index', [
            'cart' => $cartData['cart'],
            'items' => $cartData['items'],
            'subtotal' => $cartData['subtotal'],
            'delivery_fee' => $cartData['delivery_fee'],
            'grand_total' => $cartData['grand_total'],
            'item_count' => $cartData['item_count'],
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'food_id' => ['required', 'exists:foods,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $result = $this->cartService->addItem((int) $validated['food_id'], $quantity);

        if (! $result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function update(Request $request, int $itemId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:50'],
        ]);

        $result = $this->cartService->updateQuantity($itemId, (int) $validated['quantity']);

        if (! $result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function remove(int $itemId): RedirectResponse
    {
        $result = $this->cartService->removeItem($itemId);

        if (! $result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function clear(): RedirectResponse
    {
        $this->cartService->clearCart();

        return redirect()->route('cart.index')->with('info', 'Your shopping cart has been cleared.');
    }
}
