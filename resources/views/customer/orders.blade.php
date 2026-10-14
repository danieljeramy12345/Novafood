@extends('layouts.app')

@section('title', 'My Order History — NovaFood')

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">Order History</h1>
            <p class="text-sm text-slate-400 mt-1">Review all your past and present food orders.</p>
        </div>

        <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-500 text-slate-950 font-bold text-xs uppercase tracking-wider hover:bg-orange-600 transition-colors shrink-0">
            Order Again →
        </a>
    </div>

    <div class="bg-slate-900/80 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl">
        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="p-6 rounded-2xl bg-slate-950/60 border border-purple-900/30 hover:border-purple-700/50 transition-all flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-base font-black text-white">#{{ $order->order_number }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $order->status_badge_class }}">
                                    {{ $order->status_label }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ $order->created_at->format('M d, Y · h:i A') }}
                                </span>
                            </div>

                            <!-- Ordered Food Items Snapshot -->
                            <div class="text-xs text-slate-300">
                                @foreach($order->items as $item)
                                    <span class="inline-block bg-purple-950/60 border border-purple-900/40 px-2.5 py-1 rounded-lg mr-1.5 mb-1">
                                        {{ $item->quantity }}x {{ $item->food_name }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="text-xs text-slate-400">
                                Delivery Address: <span class="text-slate-300 font-medium">{{ Str::limit($order->delivery_address, 60) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-6 w-full md:w-auto border-t md:border-t-0 border-purple-900/30 pt-4 md:pt-0">
                            <div class="text-left md:text-right">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Total</span>
                                <span class="text-xl font-black text-orange-400">₱{{ number_format($order->grand_total, 2) }}</span>
                            </div>

                            <a href="{{ route('orders.show', $order) }}" class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs uppercase tracking-wider shadow transition-colors">
                                View Details →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-16 text-slate-400">
                <div class="text-4xl mb-3">🍽️</div>
                <h3 class="text-lg font-bold text-white mb-2">No past orders found</h3>
                <p class="text-xs text-slate-400 mb-6">When you place orders, their real-time progress and history will be listed here.</p>
                <a href="{{ route('menu') }}" class="px-6 py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs uppercase tracking-wider transition-colors">
                    Explore NovaFood Menu
                </a>
            </div>
        @endif
    </div>
@endsection
