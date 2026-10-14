@extends('layouts.app')

@section('title', 'Checkout — NovaFood')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-black text-white tracking-tight">Order Checkout</h1>
        <p class="text-sm text-slate-400 mt-1">Please confirm your delivery address and contact information.</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Delivery & Payment Information (Left) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Recipient & Address Card -->
                <div class="bg-slate-900/80 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl space-y-5">
                    <div class="flex items-center gap-3 border-b border-purple-900/30 pb-4">
                        <div class="w-8 h-8 rounded-xl bg-purple-900/60 text-purple-300 flex items-center justify-center font-bold text-sm">
                            1
                        </div>
                        <h2 class="text-lg font-bold text-white">Delivery Details</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Customer Name -->
                        <div>
                            <label for="customer_name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Recipient Name *</label>
                            <input 
                                type="text" 
                                id="customer_name" 
                                name="customer_name" 
                                value="{{ old('customer_name', $user->name) }}" 
                                required 
                                class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                            />
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="customer_phone" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Contact Phone *</label>
                            <input 
                                type="text" 
                                id="customer_phone" 
                                name="customer_phone" 
                                value="{{ old('customer_phone', $user->phone ?? '+63 ') }}" 
                                placeholder="+63 9XX XXX XXXX"
                                required 
                                class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                            />
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div>
                        <label for="delivery_address" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Complete Delivery Address *</label>
                        <textarea 
                            id="delivery_address" 
                            name="delivery_address" 
                            rows="3" 
                            required 
                            placeholder="House / Unit / Floor No., Street Name, Barangay, City, Metro Manila" 
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 resize-none"
                        >{{ old('delivery_address', $user->address) }}</textarea>
                    </div>

                    <!-- Delivery Notes -->
                    <div>
                        <label for="notes" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Order / Rider Instructions (Optional)</label>
                        <input 
                            type="text" 
                            id="notes" 
                            name="notes" 
                            value="{{ old('notes') }}" 
                            placeholder="e.g. Ring the doorbell, leave at front desk, extra spicy sauce please" 
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                        />
                    </div>
                </div>

                <!-- Payment Method Card -->
                <div class="bg-slate-900/80 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl space-y-4">
                    <div class="flex items-center gap-3 border-b border-purple-900/30 pb-4">
                        <div class="w-8 h-8 rounded-xl bg-purple-900/60 text-purple-300 flex items-center justify-center font-bold text-sm">
                            2
                        </div>
                        <h2 class="text-lg font-bold text-white">Payment Method</h2>
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-4 rounded-2xl bg-slate-950 border-2 border-orange-500/80 cursor-pointer shadow-md">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="cod" checked class="text-orange-500 focus:ring-orange-500 w-4 h-4 bg-slate-900 border-purple-700">
                                <div>
                                    <span class="text-sm font-bold text-white block">Cash on Delivery (COD)</span>
                                    <span class="text-xs text-slate-400">Pay cash upon receiving your hot meal from our courier.</span>
                                </div>
                            </div>
                            <span class="text-xl">💵</span>
                        </label>

                        <div class="p-4 rounded-2xl bg-slate-950/40 border border-purple-950 text-slate-500 flex items-center justify-between cursor-not-allowed">
                            <div class="flex items-center gap-3">
                                <input type="radio" disabled class="w-4 h-4 text-slate-600 bg-slate-800 border-slate-700">
                                <div>
                                    <span class="text-sm font-semibold text-slate-400">Online GCash / Maya Card (Coming Soon)</span>
                                    <span class="text-xs text-slate-500 block">Gateway integration will be activated in upcoming update.</span>
                                </div>
                            </div>
                            <span class="text-xl opacity-40">💳</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Snapshot & Submit (Right) -->
            <div class="lg:col-span-5 bg-slate-900/80 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6 sticky top-28">
                <h3 class="text-lg font-black text-white tracking-tight border-b border-purple-900/30 pb-4">
                    Order Items ({{ $items->sum('quantity') }})
                </h3>

                <!-- Scrollable Items List -->
                <div class="max-h-64 overflow-y-auto divide-y divide-purple-900/20 pr-1 space-y-2">
                    @foreach($items as $item)
                        <div class="pt-2 first:pt-0 flex items-center justify-between gap-3 text-sm">
                            <div class="truncate">
                                <div class="font-bold text-white truncate">{{ $item->food->name }}</div>
                                <div class="text-xs text-slate-400">{{ $item->quantity }} × ₱{{ number_format((float) $item->food->price, 2) }}</div>
                            </div>
                            <span class="font-black text-orange-400 shrink-0">₱{{ number_format((float) $item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Price Calculations -->
                <div class="space-y-3 text-sm border-t border-purple-900/30 pt-4">
                    <div class="flex justify-between text-slate-300">
                        <span>Subtotal</span>
                        <span class="font-bold text-white">₱{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="flex justify-between text-slate-300">
                        <span>Delivery Fee</span>
                        <span class="font-bold {{ $delivery_fee == 0 ? 'text-emerald-400' : 'text-white' }}">
                            {{ $delivery_fee > 0 ? '₱' . number_format($delivery_fee, 2) : 'FREE' }}
                        </span>
                    </div>

                    <div class="border-t border-purple-900/40 pt-4 flex justify-between items-baseline">
                        <span class="text-base font-bold text-white">Grand Total</span>
                        <span class="text-2xl font-black text-orange-400 tracking-tight">₱{{ number_format($grand_total, 2) }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 hover:from-orange-600 hover:to-amber-600 text-slate-950 font-black text-sm uppercase tracking-wider shadow-xl shadow-orange-500/20 flex items-center justify-center gap-2 transition-all duration-200 hover:scale-[1.02] active:scale-95"
                    >
                        <span>Place Order Now</span>
                        <span class="text-base">✓</span>
                    </button>

                    <p class="text-[11px] text-slate-400 text-center mt-4">
                        By placing this order, you agree to receive the order at your specified address and pay in cash upon arrival.
                    </p>
                </div>
            </div>
        </div>
    </form>
@endsection
