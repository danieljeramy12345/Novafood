<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items'])->latest();

        if ($status = $request->input('status')) {
            $query->where('order_status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();
        $statuses = Order::STATUSES;

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.food', 'statusHistories.user']);
        $allowedTransitions = Order::ALLOWED_TRANSITIONS[$order->order_status] ?? [];

        return view('admin.orders.show', compact('order', 'allowedTransitions'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => ['required', 'string'],
            'note' => ['nullable', 'string', 'max:500'],
            'payment_status' => ['nullable', 'in:pending,paid,refunded'],
        ]);

        try {
            $this->orderService->updateStatus(
                $order,
                $validated['order_status'],
                Auth::user(),
                $validated['note'] ?? null
            );

            // Optional explicit payment status update (e.g. COD collected)
            if (! empty($validated['payment_status'])) {
                $order->update(['payment_status' => $validated['payment_status']]);
            }

            return back()->with('success', "Order #{$order->order_number} status updated to {$order->status_label}.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
