@extends('layouts.app')

@section('title', "{$food->name} — NovaFood")
@section('meta_description', Str::limit($food->description, 150))

@section('content')
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs text-slate-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('menu') }}" class="hover:text-white transition-colors">Menu</a>
        <span>/</span>
        <a href="{{ route('menu', ['category' => $food->category->slug]) }}" class="hover:text-white transition-colors">{{ $food->category->name }}</a>
        <span>/</span>
        <span class="text-orange-400 font-semibold truncate">{{ $food->name }}</span>
    </nav>

    <!-- Product Details Main Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 bg-slate-900/60 border border-purple-900/40 rounded-3xl p-6 sm:p-10 shadow-2xl mb-16">
        <!-- Image Column -->
        <div class="lg:col-span-6">
            <div class="relative rounded-2xl overflow-hidden bg-slate-950 border border-purple-900/50 shadow-xl aspect-square max-h-[500px]">
                <img 
                    src="{{ $food->image_url }}" 
                    alt="{{ $food->name }}" 
                    class="w-full h-full object-cover object-center"
                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';"
                />
                
                @if($food->is_featured)
                    <span class="absolute top-4 left-4 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-orange-500 text-slate-950 shadow-lg">
                        ★ Chef Recommends
                    </span>
                @endif

                @if(! $food->is_available || $food->stock_quantity <= 0)
                    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center">
                        <span class="px-6 py-3 rounded-2xl bg-rose-600 text-white font-black text-sm uppercase tracking-wider shadow-2xl">
                            Currently Out of Stock
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Column -->
        <div class="lg:col-span-6 flex flex-col justify-between space-y-6">
            <div>
                <!-- Category Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-950/80 border border-purple-800/50 text-xs font-bold text-purple-300 mb-3">
                    <span>{{ $food->category->icon ?: '🍽️' }}</span>
                    <span>{{ $food->category->name }}</span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight mb-3">
                    {{ $food->name }}
                </h1>

                <div class="flex items-center gap-4 mb-6">
                    <span class="text-3xl font-black text-orange-400">
                        {{ $food->formatted_price }}
                    </span>
                    @if($food->is_available && $food->stock_quantity > 0)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-950 text-emerald-400 border border-emerald-800/60">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            In Stock ({{ $food->stock_quantity }} available)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-950 text-rose-400 border border-rose-800/60">
                            Unavailable
                        </span>
                    @endif
                </div>

                <div class="space-y-2 border-t border-b border-purple-900/30 py-5 my-4">
                    <h3 class="text-xs uppercase font-bold tracking-wider text-slate-400">Description</h3>
                    <p class="text-sm text-slate-300 leading-relaxed font-normal">
                        {{ $food->description }}
                    </p>
                </div>

                <!-- Dietary & Preparation Highlights -->
                <div class="grid grid-cols-2 gap-3 text-xs text-slate-400 py-2">
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400">✓</span> Made to order fresh
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400">✓</span> Insulated thermal packaging
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400">✓</span> Estimated delivery: 30-45m
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400">✓</span> Cash on Delivery accepted
                    </div>
                </div>
            </div>

            <!-- Add to Cart Form -->
            @if($food->is_available && $food->stock_quantity > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="pt-4 border-t border-purple-900/30">
                    @csrf
                    <input type="hidden" name="food_id" value="{{ $food->id }}">

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                        <!-- Quantity Selector -->
                        <div class="flex items-center rounded-2xl bg-slate-950 border border-purple-800/60 p-1 shrink-0 w-36 justify-between">
                            <button 
                                type="button" 
                                onclick="const input = document.getElementById('qty-input'); if(input.value > 1) input.value = parseInt(input.value) - 1;" 
                                class="w-10 h-10 rounded-xl bg-purple-900/40 hover:bg-purple-800 text-white flex items-center justify-center font-bold text-lg transition-colors"
                            >
                                -
                            </button>
                            <input 
                                type="number" 
                                id="qty-input" 
                                name="quantity" 
                                value="1" 
                                min="1" 
                                max="{{ min(50, $food->stock_quantity) }}" 
                                class="w-12 text-center bg-transparent border-0 text-white font-bold focus:outline-none focus:ring-0"
                            />
                            <button 
                                type="button" 
                                onclick="const input = document.getElementById('qty-input'); if(input.value < {{ min(50, $food->stock_quantity) }}) input.value = parseInt(input.value) + 1;" 
                                class="w-10 h-10 rounded-xl bg-purple-900/40 hover:bg-purple-800 text-white flex items-center justify-center font-bold text-lg transition-colors"
                            >
                                +
                            </button>
                        </div>

                        <!-- Add Button -->
                        <button 
                            type="submit" 
                            class="flex-1 py-4 px-6 rounded-2xl bg-gradient-to-r from-purple-600 via-purple-700 to-orange-500 hover:from-purple-500 hover:to-orange-400 text-white font-black text-sm uppercase tracking-wider shadow-xl shadow-purple-600/30 hover:shadow-orange-500/40 flex items-center justify-center gap-2 active:scale-95 transition-all duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Add to Cart
                        </button>
                    </div>
                </form>
            @else
                <div class="p-4 rounded-2xl bg-rose-950/40 border border-rose-900/50 text-center text-sm font-semibold text-rose-300">
                    This item is temporarily unavailable for ordering. Check back soon!
                </div>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedFoods->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-black text-white tracking-tight mb-8">
                More from <span class="text-orange-400">{{ $food->category->name }}</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedFoods as $related)
                    <x-food-card :food="$related" />
                @endforeach
            </div>
        </div>
    @endif
@endsection
