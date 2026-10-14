<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService
    ) {}

    public function index(): View|RedirectResponse
    {
        $cartData = $this->cartService->calculateTotals();

        if ($cartData['items']->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', 'Your cart is empty. Add some delicious meals before checking out!');
        }

        $user = Auth::user();

        return view('checkout.index', [
            'user' => $user,
            'items' => $cartData['items'],
            'subtotal' => $cartData['subtotal'],
            'delivery_fee' => $cartData['delivery_fee'],
            'grand_total' => $cartData['grand_total'],
        ]);
    }

    public function process(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'delivery_address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cod'],
        ], [
            'customer_name.required' => 'Please provide recipient full name.',
            'customer_phone.required' => 'Please provide a valid contact phone number.',
            'delivery_address.required' => 'Delivery address is required for food drop-off.',
            'payment_method.in' => 'Currently only Cash on Delivery (COD) is supported.',
        ]);

        $user = Auth::user();

        try {
            $order = $this->orderService->createOrder($user, $validated);

            // Optionally remember delivery address for customer's profile if empty
            if (empty($user->address)) {
                $user->update([
                    'address' => $validated['delivery_address'],
                    'phone' => $validated['customer_phone'],
                ]);
            }

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order #' . $order->order_number . ' placed successfully! Your food is being prepared.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
