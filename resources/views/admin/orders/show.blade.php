@extends('layouts.admin')

@section('title', "Manage Order #{$order->order_number}")
@section('page_title', "Order #{$order->order_number}")
@section('page_subtitle', "Inspect items, update fulfillment status, and verify payment.")

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-xs text-purple-400 hover:text-white transition-colors">
            ← Back to Orders List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Order Items & Timeline (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Items Table -->
            <div class="bg-slate-900 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl">
                <div class="flex items-center justify-between border-b border-purple-900/30 pb-4 mb-6">
                    <h3 class="text-base font-bold text-white">Ordered Meals Snapshot ({{ $order->items->count() }})</h3>
                    <span class="text-xs text-slate-400">Fixed prices recorded at checkout</span>
                </div>

                <div class="divide-y divide-purple-900/20">
                    @foreach($order->items as $item)
                        <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                @if($item->food)
                                    <img 
                                        src="{{ $item->food->image_url }}" 
                                        alt="{{ $item->food_name }}" 
                                        class="w-12 h-12 rounded-xl object-cover bg-slate-950 shrink-0 border border-purple-900/40"
                                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';"
                                    />
                                @endif
                                <div>
                                    <span class="font-bold text-white text-sm block">{{ $item->food_name }}</span>
                                    <span class="text-xs text-slate-400">{{ $item->quantity }} × ₱{{ number_format((float) $item->unit_price, 2) }}</span>
                                </div>
                            </div>
                            <span class="font-black text-orange-400 text-sm">₱{{ number_format((float) $item->line_subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-purple-900/30 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-400">
                        <span>Items Subtotal:</span>
                        <span class="font-bold text-white">₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Delivery Fee:</span>
                        <span class="font-bold text-white">{{ $order->delivery_fee > 0 ? '₱' . number_format($order->delivery_fee, 2) : 'FREE' }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-white pt-2 border-t border-purple-900/20">
                        <span>Grand Total:</span>
                        <span class="text-xl font-black text-orange-400">₱{{ number_format($order->grand_total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Transition History -->
            <div class="bg-slate-900 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl">
                <h3 class="text-base font-bold text-white mb-6 border-b border-purple-900/30 pb-3">Status Audit Log</h3>

                <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-purple-900/60">
                    @foreach($order->statusHistories as $history)
                        <div class="relative">
                            <div class="absolute -left-[27px] top-1 w-3.5 h-3.5 rounded-full bg-purple-500 border-2 border-slate-900"></div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-white uppercase">{{ $history->new_status_label }}</span>
                                    @if($history->user)
                                        <span class="text-[10px] text-purple-300 bg-purple-950/80 px-2 py-0.5 rounded">by {{ $history->user->name }}</span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-slate-400 block">{{ $history->created_at->format('M d, Y · g:i A') }}</span>
                                @if($history->note)
                                    <p class="text-xs text-slate-300 italic bg-slate-950 p-2.5 rounded-xl border border-purple-950">
                                        {{ $history->note }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Control & Customer Details (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Status Update Action Card -->
            <div class="bg-slate-900 border border-purple-900/40 rounded-3xl p-6 shadow-xl space-y-5">
                <div class="flex items-center justify-between border-b border-purple-900/30 pb-3">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Update Fulfillment</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                </div>

                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="order_status" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Next Status Transition *</label>
                        <select 
                            id="order_status" 
                            name="order_status" 
                            required 
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-xs font-bold text-white focus:outline-none focus:border-orange-500 cursor-pointer"
                        >
                            <option value="{{ $order->order_status }}" selected>Current: {{ $order->status_label }}</option>
                            @foreach($allowedTransitions as $nextStatus)
                                <option value="{{ $nextStatus }}">
                                    → Change to: {{ \App\Models\Order::STATUSES[$nextStatus] ?? $nextStatus }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label for="payment_status" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Payment Verification</label>
                        <select 
                            id="payment_status" 
                            name="payment_status" 
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-xs font-bold text-white focus:outline-none focus:border-orange-500 cursor-pointer"
                        >
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending Payment (COD)</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid (Cash Collected from Courier)</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <!-- Transition Note -->
                    <div>
                        <label for="note" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Status Audit Note (Optional)</label>
                        <input 
                            type="text" 
                            id="note" 
                            name="note" 
                            placeholder="e.g. Courier assigned, food packed in hot bag"
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-orange-500"
                        />
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-orange-500/20 hover:scale-105 transition-all"
                    >
                        Save Status Change
                    </button>
                </form>
            </div>

            <!-- Customer & Delivery Snapshot -->
            <div class="bg-slate-900 border border-purple-900/40 rounded-3xl p-6 shadow-xl space-y-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-purple-900/30 pb-3">Customer & Delivery Snapshot</h3>

                <div class="text-xs space-y-3">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Recipient</span>
                        <span class="font-bold text-white text-sm">{{ $order->customer_name }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Phone Number</span>
                        <a href="tel:{{ $order->customer_phone }}" class="text-orange-400 font-bold hover:underline">
                            {{ $order->customer_phone }}
                        </a>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Delivery Address</span>
                        <p class="text-slate-200 leading-relaxed bg-slate-950 p-3 rounded-xl border border-purple-950">
                            {{ $order->delivery_address }}
                        </p>
                    </div>

                    @if($order->notes)
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Customer Notes</span>
                            <p class="text-slate-300 italic">{{ $order->notes }}</p>
                        </div>
                    @endif

                    <div class="pt-2 border-t border-purple-900/30">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Account User</span>
                        @if($order->user)
                            <a href="{{ route('admin.users.show', $order->user) }}" class="text-purple-300 hover:underline">
                                {{ $order->user->name }} ({{ $order->user->email }})
                            </a>
                        @else
                            <span class="text-slate-500">Guest Checkout</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
