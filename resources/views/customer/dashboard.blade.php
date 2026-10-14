@extends('layouts.app')

@section('title', 'Customer Dashboard — NovaFood')

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-orange-400 block mb-1">Customer Portal</span>
            <h1 class="text-3xl font-black text-white tracking-tight">Welcome, {{ $user->name }}!</h1>
            <p class="text-sm text-slate-400 mt-0.5">Manage your food orders and account details.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-md hover:scale-105 transition-all">
                <span>🍽️</span> Order Food Now
            </a>
            <a href="{{ route('customer.profile') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 border border-purple-800/60 text-slate-200 hover:text-white font-semibold text-xs transition-colors">
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
        <div class="p-6 rounded-3xl bg-slate-900/80 border border-purple-900/40 shadow-xl flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-950 border border-purple-800/60 flex items-center justify-center text-2xl text-purple-400">
                📦
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Orders</span>
                <span class="text-2xl font-black text-white">{{ $totalOrdersCount }}</span>
            </div>
        </div>

        <div class="p-6 rounded-3xl bg-slate-900/80 border border-purple-900/40 shadow-xl flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-950 border border-purple-800/60 flex items-center justify-center text-2xl text-emerald-400">
                ⚡
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Active Orders</span>
                <span class="text-2xl font-black text-orange-400">{{ $activeOrders->count() }}</span>
            </div>
        </div>

        <div class="p-6 rounded-3xl bg-slate-900/80 border border-purple-900/40 shadow-xl flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-950 border border-purple-800/60 flex items-center justify-center text-2xl text-orange-400">
                💳
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Food Spent</span>
                <span class="text-2xl font-black text-white">₱{{ number_format($totalSpent, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Active Orders Banner (if any) -->
    @if($activeOrders->count() > 0)
        <div class="mb-10">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-orange-400 animate-ping"></span>
                Active Food In Preparation & Delivery
            </h2>

            <div class="space-y-4">
                @foreach($activeOrders as $active)
                    <div class="p-6 rounded-3xl bg-gradient-to-r from-purple-950/80 via-slate-900 to-slate-900 border border-purple-800/60 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-base font-black text-white tracking-tight">Order #{{ $active->order_number }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $active->status_badge_class }}">
                                    {{ $active->status_label }}
                                </span>
                            </div>
                            <div class="text-xs text-slate-400">
                                Placed on {{ $active->created_at->format('M d, Y h:i A') }} &middot; {{ $active->items->count() }} items &middot; Total: <strong class="text-orange-400">₱{{ number_format($active->grand_total, 2) }}</strong>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('orders.show', $active) }}" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs uppercase tracking-wider transition-colors shadow">
                                Track Live Status →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recent Orders List -->
    <div class="bg-slate-900/80 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="flex items-center justify-between border-b border-purple-900/30 pb-4 mb-6">
            <h2 class="text-lg font-bold text-white">Recent Orders</h2>
            <a href="{{ route('customer.orders') }}" class="text-xs font-bold text-purple-400 hover:text-orange-400 transition-colors">
                View All Orders →
            </a>
        </div>

        @if($recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="text-slate-400 uppercase tracking-wider border-b border-purple-900/40 pb-2">
                        <tr>
                            <th class="py-3 px-4">Order No.</th>
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Items</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-900/20">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-purple-950/20 transition-colors">
                                <td class="py-4 px-4 font-bold text-white">
                                    {{ $order->order_number }}
                                </td>
                                <td class="py-4 px-4 text-slate-400">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-4 px-4">
                                    {{ $order->items->pluck('food_name')->take(2)->implode(', ') }}
                                    @if($order->items->count() > 2)
                                        <span class="text-slate-500">+{{ $order->items->count() - 2 }} more</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 font-black text-orange-400">
                                    ₱{{ number_format($order->grand_total, 2) }}
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $order->status_badge_class }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-purple-900 text-slate-200 hover:text-white font-semibold text-xs transition-colors">
                                        Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-slate-400 text-sm">
                You haven't placed any food orders yet. 
                <a href="{{ route('menu') }}" class="text-orange-400 font-bold hover:underline block mt-2">Explore our menu and order now!</a>
            </div>
        @endif
    </div>
@endsection
