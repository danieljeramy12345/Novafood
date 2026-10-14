@extends('layouts.admin')

@section('title', 'Orders Management')
@section('page_title', 'Customer Orders')
@section('page_subtitle', 'Monitor incoming orders, transition delivery states, and verify COD receipts.')

@section('content')
    <div class="space-y-6">
        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none border-b border-purple-900/40">
            <a 
                href="{{ route('admin.orders.index', array_filter(['search' => request('search')])) }}" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ !request('status') ? 'bg-orange-500 text-slate-950 shadow-md' : 'text-slate-400 hover:text-white bg-slate-900 border border-purple-950' }}"
            >
                All Orders
            </a>

            @foreach($statuses as $key => $label)
                <a 
                    href="{{ route('admin.orders.index', array_filter(['status' => $key, 'search' => request('search')])) }}" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ request('status') === $key ? 'bg-orange-500 text-slate-950 shadow-md' : 'text-slate-400 hover:text-white bg-slate-900 border border-purple-950' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Search Bar -->
        <div class="flex items-center justify-between gap-4">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="flex items-center gap-3 max-w-md w-full">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative w-full">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search by Order #, Customer Name, or Phone..." 
                        class="w-full bg-slate-900 border border-purple-800/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                    />
                </div>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-purple-700 hover:bg-purple-600 text-white font-bold text-xs uppercase tracking-wider shrink-0">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.orders.index', array_filter(['status' => request('status')])) }}" class="text-xs text-slate-400 hover:text-white shrink-0">Clear</a>
                @endif
            </form>
        </div>

        <!-- Orders Table -->
        <div class="bg-slate-900 border border-purple-900/40 rounded-3xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="text-slate-400 uppercase tracking-wider border-b border-purple-900/40 bg-slate-950/40">
                        <tr>
                            <th class="py-3.5 px-4">Order #</th>
                            <th class="py-3.5 px-4">Customer</th>
                            <th class="py-3.5 px-4">Date Placed</th>
                            <th class="py-3.5 px-4">Address</th>
                            <th class="py-3.5 px-4">Total</th>
                            <th class="py-3.5 px-4">Payment</th>
                            <th class="py-3.5 px-4">Order Status</th>
                            <th class="py-3.5 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-900/20">
                        @forelse($orders as $order)
                            <tr class="hover:bg-purple-950/20 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-white">
                                    #{{ $order->order_number }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-white">{{ $order->customer_name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $order->customer_phone }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-400">
                                    {{ $order->created_at->format('M d, Y') }}
                                    <span class="block text-[10px] text-slate-500">{{ $order->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-300 max-w-xs truncate" title="{{ $order->delivery_address }}">
                                    {{ Str::limit($order->delivery_address, 40) }}
                                </td>
                                <td class="py-3.5 px-4 font-black text-orange-400 text-sm">
                                    ₱{{ number_format((float) $order->grand_total, 2) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-950 text-emerald-300 border border-emerald-800' : 'bg-amber-950 text-amber-300 border border-amber-800' }}">
                                        COD &middot; {{ $order->payment_status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $order->status_badge_class }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs uppercase tracking-wider transition-colors shadow">
                                        Manage →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    No orders found matching the filter criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="p-4 border-t border-purple-900/30">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
