<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('order_status', Order::STATUS_PENDING)->count(),
            'preparing_orders' => Order::whereIn('order_status', [Order::STATUS_CONFIRMED, Order::STATUS_PREPARING])->count(),
            'out_for_delivery' => Order::where('order_status', Order::STATUS_OUT_FOR_DELIVERY)->count(),
            'completed_orders' => Order::where('order_status', Order::STATUS_DELIVERED)->count(),
            'cancelled_orders' => Order::where('order_status', Order::STATUS_CANCELLED)->count(),
            'total_foods' => Food::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_revenue' => Order::where('order_status', Order::STATUS_DELIVERED)->sum('grand_total'),
        ];

        $recentOrders = Order::with(['user', 'items.food'])
            ->latest()
            ->take(8)
            ->get();

        $lowStockFoods = Food::where('stock_quantity', '<=', 15)
            ->where('is_available', true)
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockFoods'));
    }
}
