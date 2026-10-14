<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function dashboard(): View
    {
        $user = Auth::user();

        $recentOrders = Order::where('user_id', $user->id)
            ->with('items')
            ->latest()
            ->take(5)
            ->get();

        $activeOrders = Order::where('user_id', $user->id)
            ->whereNotIn('order_status', [Order::STATUS_DELIVERED, Order::STATUS_CANCELLED])
            ->latest()
            ->get();

        $totalSpent = Order::where('user_id', $user->id)
            ->where('order_status', Order::STATUS_DELIVERED)
            ->sum('grand_total');

        $totalOrdersCount = Order::where('user_id', $user->id)->count();

        return view('customer.dashboard', compact('user', 'recentOrders', 'activeOrders', 'totalSpent', 'totalOrdersCount'));
    }

    public function orders(): View
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->with(['items.food'])
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders', 'user'));
    }

    public function orderShow(Order $order): View
    {
        // Authorization check
        if (! Auth::user()->isAdmin() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['items.food', 'statusHistories.user']);

        return view('customer.order-show', compact('order'));
    }

    public function cancelOrder(Request $request, Order $order): RedirectResponse
    {
        // Authorization check
        if (! Auth::user()->isAdmin() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action on this order.');
        }

        if (! $order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled as it is already being prepared or out for delivery.');
        }

        try {
            $reason = $request->input('reason', 'Cancelled by customer.');
            $this->orderService->updateStatus($order, Order::STATUS_CANCELLED, Auth::user(), "Order cancelled: {$reason}");

            return back()->with('success', 'Order #' . $order->order_number . ' has been cancelled successfully.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function profile(): View
    {
        $user = Auth::user();

        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'] ?? null;

        if (! empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'Your profile information has been updated successfully.');
    }
}
