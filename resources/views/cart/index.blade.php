@extends('layouts.app')

@section('title', 'Your Shopping Cart — NovaFood')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-black text-white tracking-tight">Your Food Basket</h1>
        <p class="text-sm text-slate-400 mt-1">Review your selections, update quantities, and proceed to checkout.</p>
    </div>

    @if($items->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Items Table/List -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-slate-900/70 border border-purple-900/40 rounded-3xl overflow-hidden shadow-xl">
                    <div class="p-6 border-b border-purple-900/30 flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-purple-300">
                            {{ $item_count }} {{ Str::plural('Item', $item_count) }} in Cart
                        </span>

                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-rose-400 hover:text-rose-300 transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Clear Basket
                            </button>
                        </form>
                    </div>

                    <!-- Items List -->
                    <div class="divide-y divide-purple-900/30">
                        @foreach($items as $item)
                            <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4 min-w-0">
                                    <img 
                                        src="{{ $item->food->image_url }}" 
                                        alt="{{ $item->food->name }}" 
                                        class="w-20 h-20 rounded-2xl object-cover bg-slate-950 shrink-0 border border-purple-900/50"
                                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';"
                                    />
                                    <div class="truncate">
                                        <a href="{{ route('foods.show', $item->food->slug) }}" class="text-base font-bold text-white hover:text-orange-400 transition-colors truncate block">
                                            {{ $item->food->name }}
                                        </a>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            Unit Price: <span class="font-semibold text-slate-300">₱{{ number_format((float) $item->food->price, 2) }}</span>
                                        </div>
                                        @if(! $item->food->is_available)
                                            <span class="inline-block mt-1 text-[11px] font-bold text-rose-400 bg-rose-950/60 px-2 py-0.5 rounded-md">Unavailable</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Quantity Controls & Line Subtotal -->
                                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                                    <!-- Quantity form -->
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-1.5">
                                        @csrf
                                        @method('PATCH')
                                        <button 
                                            type="submit" 
                                            name="quantity" 
                                            value="{{ max(0, $item->quantity - 1) }}"
                                            class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-purple-900 text-white font-bold text-sm flex items-center justify-center transition-colors"
                                            title="Decrease quantity"
                                        >
                                            -
                                        </button>
                                        <span class="w-8 text-center text-sm font-bold text-white">
                                            {{ $item->quantity }}
                                        </span>
                                        <button 
                                            type="submit" 
                                            name="quantity" 
                                            value="{{ $item->quantity + 1 }}"
                                            class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-purple-900 text-white font-bold text-sm flex items-center justify-center transition-colors"
                                            title="Increase quantity"
                                        >
                                            +
                                        </button>
                                    </form>

                                    <!-- Subtotal -->
                                    <div class="text-right min-w-[90px]">
                                        <span class="text-base font-black text-orange-400">
                                            ₱{{ number_format((float) $item->subtotal, 2) }}
                                        </span>
                                    </div>

                                    <!-- Delete Item -->
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-400 transition-colors" title="Remove item">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 text-xs font-bold text-purple-400 hover:text-orange-400 transition-colors">
                        ← Continue Browsing Menu
                    </a>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="lg:col-span-4 bg-slate-900/80 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6 sticky top-28">
                <h3 class="text-lg font-black text-white tracking-tight border-b border-purple-900/30 pb-4">
                    Order Summary
                </h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-slate-300">
                        <span>Items Subtotal</span>
                        <span class="font-bold text-white">₱{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="flex justify-between text-slate-300">
                        <span class="flex items-center gap-1.5">
                            Delivery Fee
                            @if($delivery_fee == 0)
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400">FREE</span>
                            @endif
                        </span>
                        <span class="font-bold {{ $delivery_fee == 0 ? 'text-emerald-400' : 'text-white' }}">
                            {{ $delivery_fee > 0 ? '₱' . number_format($delivery_fee, 2) : '₱0.00' }}
                        </span>
                    </div>

                    @if($subtotal < 800)
                        <div class="p-3 rounded-xl bg-purple-950/60 border border-purple-800/40 text-[11px] text-purple-200">
                            💡 Add <strong class="text-orange-400">₱{{ number_format(800 - $subtotal, 2) }}</strong> more to enjoy <span class="text-emerald-400 font-bold">FREE Delivery</span>!
                        </div>
                    @endif

                    <div class="border-t border-purple-900/40 pt-4 flex justify-between items-baseline">
                        <span class="text-base font-bold text-white">Grand Total</span>
                        <span class="text-2xl font-black text-orange-400 tracking-tight">₱{{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a 
                        href="{{ route('checkout.index') }}" 
                        class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 hover:from-orange-600 hover:to-amber-600 text-slate-950 font-black text-sm uppercase tracking-wider shadow-xl shadow-orange-500/20 flex items-center justify-center gap-2 transition-all duration-200 hover:scale-[1.02] active:scale-95"
                    >
                        Proceed to Checkout →
                    </a>

                    <div class="flex items-center justify-center gap-2 text-[11px] text-slate-400 text-center mt-4">
                        <span>🔒</span> Safe checkout via Cash on Delivery
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart State -->
        <div class="max-w-md mx-auto text-center py-16 px-4 bg-slate-900/60 border border-purple-900/40 rounded-3xl shadow-2xl my-8">
            <div class="w-24 h-24 rounded-full bg-purple-950/80 border border-purple-800/50 flex items-center justify-center text-4xl mx-auto mb-6">
                🛒
            </div>
            <h2 class="text-2xl font-black text-white mb-2">Your cart is currently empty</h2>
            <p class="text-sm text-slate-400 mb-8 leading-relaxed">
                You haven't added any appetizing foods to your basket yet. Check out our latest burgers, pizzas, and combos!
            </p>
            <a 
                href="{{ route('menu') }}" 
                class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-gradient-to-r from-purple-600 to-orange-500 hover:from-purple-500 hover:to-orange-400 text-white font-bold text-xs uppercase tracking-wider shadow-xl shadow-purple-600/30 transition-all duration-200 hover:scale-105"
            >
                Start Browsing Menu
            </a>
        </div>
    @endif
@endsection
