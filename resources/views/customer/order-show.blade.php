@extends('layouts.app')

@section('title', "Order #{$order->order_number} Details — NovaFood")

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.orders') }}" class="text-xs text-purple-400 hover:text-white transition-colors">← Back to Orders</a>
            </div>
            <h1 class="text-3xl font-black text-white tracking-tight mt-1 flex items-center gap-3 flex-wrap">
                <span>Order #{{ $order->order_number }}</span>
                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $order->status_badge_class }}">
                    {{ $order->status_label }}
                </span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Placed on {{ $order->created_at->format('l, F j, Y \a\t g:i A') }}</p>
        </div>

        @if($order->canBeCancelled())
            <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.');">
                @csrf
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-rose-600/20 hover:bg-rose-600 border border-rose-500/40 text-rose-300 hover:text-white text-xs font-bold uppercase tracking-wider transition-all">
                    Cancel Order
                </button>
            </form>
        @endif
    </div>

    <!-- ETA Banner if active -->
    @if(! in_array($order->order_status, ['delivered', 'cancelled']))
        <div class="mb-8 p-6 rounded-3xl bg-gradient-to-r from-purple-950/90 via-slate-900 to-slate-900 border border-purple-800/60 shadow-xl flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-500 text-slate-950 flex items-center justify-center text-2xl font-black shadow-lg shadow-orange-500/20">
                    🛵
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-orange-400 block">Estimated Arrival</span>
                    <span class="text-lg font-black text-white">
                        {{ $order->estimated_delivery_at ? $order->estimated_delivery_at->format('g:i A') : '30-45 minutes' }}
                    </span>
                </div>
            </div>
            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-purple-900/60 text-purple-200 border border-purple-700/40 animate-pulse">
                Food In Progress
            </span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Order Items & Timeline (Left) -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Items Table -->
            <div class="bg-slate-900/80 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl">
                <h2 class="text-lg font-bold text-white mb-6 border-b border-purple-900/30 pb-3">Ordered Items</h2>

                <div class="divide-y divide-purple-900/30">
                    @foreach($order->items as $item)
                        <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                @if($item->food)
                                    <img 
                                        src="{{ $item->food->image_url }}" 
                                        alt="{{ $item->food_name }}" 
                                        class="w-16 h-16 rounded-xl object-cover bg-slate-950 shrink-0 border border-purple-900/40"
                                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';"
                                    />
                                @endif
                                <div>
                                    <h4 class="text-sm font-bold text-white">{{ $item->food_name }}</h4>
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        {{ $item->quantity }} × ₱{{ number_format((float) $item->unit_price, 2) }}
                                    </div>
                                </div>
                            </div>

                            <span class="text-base font-black text-orange-400 shrink-0">
                                ₱{{ number_format((float) $item->line_subtotal, 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Status History Timeline -->
            <div class="bg-slate-900/80 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl">
                <h2 class="text-lg font-bold text-white mb-6 border-b border-purple-900/30 pb-3">Order Status Updates</h2>

                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-purple-900/60">
                    @foreach($order->statusHistories as $history)
                        <div class="relative">
                            <div class="absolute -left-[27px] top-1 w-3.5 h-3.5 rounded-full bg-orange-500 border-2 border-slate-900 shadow"></div>
                            <div>
                                <span class="text-xs font-bold text-white uppercase tracking-wider block">
                                    {{ $history->new_status_label }}
                                </span>
                                <span class="text-[11px] text-slate-400 block mt-0.5">
                                    {{ $history->created_at->format('M d, Y · h:i A') }}
                                </span>
                                @if($history->note)
                                    <p class="text-xs text-slate-300 mt-1 italic bg-slate-950/60 p-2.5 rounded-xl border border-purple-950">
                                        {{ $history->note }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary & Delivery Address Snapshot (Right) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Payment & Price Summary -->
            <div class="bg-slate-900/80 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl space-y-4">
                <h3 class="text-base font-black text-white tracking-tight border-b border-purple-900/30 pb-3">Payment Summary</h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between text-slate-300">
                        <span>Items Subtotal</span>
                        <span class="font-bold text-white">₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>

                    <div class="flex justify-between text-slate-300">
                        <span>Delivery Fee</span>
                        <span class="font-bold text-white">{{ $order->delivery_fee > 0 ? '₱' . number_format($order->delivery_fee, 2) : 'FREE' }}</span>
                    </div>

                    <div class="border-t border-purple-900/40 pt-3 flex justify-between items-baseline text-sm">
                        <span class="font-bold text-white">Grand Total</span>
                        <span class="text-xl font-black text-orange-400">₱{{ number_format($order->grand_total, 2) }}</span>
                    </div>
                </div>

                <div class="border-t border-purple-900/30 pt-4 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Payment Method:</span>
                        <span class="font-bold text-white uppercase">Cash on Delivery (COD)</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Payment Status:</span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-300' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer & Delivery Snapshot -->
            <div class="bg-slate-900/80 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl space-y-3">
                <h3 class="text-base font-black text-white tracking-tight border-b border-purple-900/30 pb-3">Delivery Information</h3>

                <div class="text-xs space-y-2">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Recipient</span>
                        <span class="font-bold text-white text-sm">{{ $order->customer_name }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Phone Number</span>
                        <span class="text-slate-200">{{ $order->customer_phone }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Delivery Address</span>
                        <p class="text-slate-200 leading-relaxed">{{ $order->delivery_address }}</p>
                    </div>

                    @if($order->notes)
                        <div class="pt-2 border-t border-purple-900/20">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Rider Note</span>
                            <p class="text-slate-300 italic">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
