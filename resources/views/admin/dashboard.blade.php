@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'System Overview & Analytics')
@section('page_subtitle', 'Live tracking of orders, sales revenue, food catalog, and customer metrics.')

@section('content')
    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="p-6 rounded-3xl bg-slate-900 border border-purple-900/40 shadow-xl flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Net Revenue</span>
                <span class="p-2 rounded-xl bg-emerald-950 text-emerald-400 text-lg">💰</span>
            </div>
            <div>
                <span class="text-3xl font-black text-emerald-400 tracking-tight">₱{{ number_format($stats['total_revenue'], 2) }}</span>
                <span class="text-[11px] text-slate-400 block mt-1">From {{ $stats['completed_orders'] }} delivered orders</span>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="p-6 rounded-3xl bg-slate-900 border border-purple-900/40 shadow-xl flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Orders Placed</span>
                <span class="p-2 rounded-xl bg-purple-950 text-purple-400 text-lg">📦</span>
            </div>
            <div>
                <span class="text-3xl font-black text-white tracking-tight">{{ $stats['total_orders'] }}</span>
                <span class="text-[11px] text-amber-400 block mt-1">{{ $stats['pending_orders'] }} pending verification</span>
            </div>
        </div>

        <!-- Active Kitchen/Delivery Card -->
        <div class="p-6 rounded-3xl bg-slate-900 border border-purple-900/40 shadow-xl flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kitchen & Transit</span>
                <span class="p-2 rounded-xl bg-orange-950 text-orange-400 text-lg">⚡</span>
            </div>
            <div>
                <span class="text-3xl font-black text-orange-400 tracking-tight">
                    {{ $stats['preparing_orders'] + $stats['out_for_delivery'] }}
                </span>
                <span class="text-[11px] text-slate-400 block mt-1">
                    {{ $stats['preparing_orders'] }} cooking &middot; {{ $stats['out_for_delivery'] }} on the road
                </span>
            </div>
        </div>

        <!-- Customer Accounts Card -->
        <div class="p-6 rounded-3xl bg-slate-900 border border-purple-900/40 shadow-xl flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Registered Customers</span>
                <span class="p-2 rounded-xl bg-blue-950 text-blue-400 text-lg">👥</span>
            </div>
            <div>
                <span class="text-3xl font-black text-white tracking-tight">{{ $stats['total_customers'] }}</span>
                <span class="text-[11px] text-slate-400 block mt-1">{{ $stats['total_foods'] }} active catalog meals</span>
            </div>
        </div>
    </div>

    <!-- Secondary Split: Recent Orders & Stock Alert -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent Orders (8 cols) -->
        <div class="lg:col-span-8 bg-slate-900 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-white">Recent Customer Orders</h3>
                    <p class="text-xs text-slate-400">Latest orders submitted across the platform</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-purple-400 hover:text-orange-400 transition-colors">
                    Manage All Orders →
                </a>
            </div>

            @if($recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="text-slate-400 uppercase tracking-wider border-b border-purple-900/40">
                            <tr>
                                <th class="py-3 px-3">Order</th>
                                <th class="py-3 px-3">Customer</th>
                                <th class="py-3 px-3">Items</th>
                                <th class="py-3 px-3">Total</th>
                                <th class="py-3 px-3">Status</th>
                                <th class="py-3 px-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-purple-900/20">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-purple-950/20 transition-colors">
                                    <td class="py-3 px-3 font-bold text-white">
                                        #{{ $order->order_number }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="font-medium text-white">{{ $order->customer_name }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td class="py-3 px-3">
                                        {{ $order->items->count() }} items
                                    </td>
                                    <td class="py-3 px-3 font-black text-orange-400">
                                        ₱{{ number_format($order->grand_total, 2) }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $order->status_badge_class }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-purple-900 text-slate-200 text-xs font-semibold transition-colors">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-10 text-slate-400 text-xs">
                    No orders have been submitted yet.
                </div>
            @endif
        </div>

        <!-- Inventory Watchlist & Quick Actions (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-slate-900 border border-purple-900/40 rounded-3xl p-6 shadow-xl space-y-3">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-purple-900/30 pb-3">Quick Actions</h3>

                <a href="{{ route('admin.foods.create') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-purple-950/60 hover:bg-purple-900/80 border border-purple-800/40 text-xs font-bold text-white transition-all">
                    <span class="flex items-center gap-2"><span>➕</span> Add New Food Item</span>
                    <span>→</span>
                </a>

                <a href="{{ route('admin.categories.create') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-800/80 hover:bg-slate-800 border border-purple-900/40 text-xs font-bold text-slate-200 transition-all">
                    <span class="flex items-center gap-2"><span>📂</span> Add Category</span>
                    <span>→</span>
                </a>

                <a href="{{ route('admin.orders.index') }}?status=pending" class="w-full flex items-center justify-between p-3 rounded-xl bg-amber-950/40 hover:bg-amber-950/60 border border-amber-800/40 text-xs font-bold text-amber-300 transition-all">
                    <span class="flex items-center gap-2"><span>⚡</span> View Pending Orders</span>
                    <span>{{ $stats['pending_orders'] }}</span>
                </a>
            </div>

            <!-- Low Stock Alerts -->
            <div class="bg-slate-900 border border-purple-900/40 rounded-3xl p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-purple-900/30 pb-3">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Inventory Low Stock Alert</h3>
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                </div>

                @if($lowStockFoods->count() > 0)
                    <div class="space-y-3">
                        @foreach($lowStockFoods as $food)
                            <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-slate-950 border border-purple-950">
                                <span class="font-bold text-white truncate max-w-[150px]">{{ $food->name }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-black {{ $food->stock_quantity <= 5 ? 'bg-rose-950 text-rose-300' : 'bg-amber-950 text-amber-300' }}">
                                    {{ $food->stock_quantity }} left
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400">All available food products currently have adequate stock levels.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
