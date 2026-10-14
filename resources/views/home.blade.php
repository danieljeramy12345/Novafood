@extends('layouts.app')

@section('title', 'NovaFood — Delicious Food, Delivered to You')

@section('fullwidth')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-purple-950/80 via-slate-950 to-slate-950 pt-12 pb-20 md:py-24 border-b border-purple-900/30">
        <!-- Background Ambient Glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-purple-600/15 blur-[120px] pointer-events-none rounded-full"></div>
        <div class="absolute top-1/3 right-10 w-72 h-72 bg-orange-500/10 blur-[100px] pointer-events-none rounded-full"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-purple-900/50 border border-purple-700/50 text-xs font-bold text-orange-400 shadow-sm">
                        <span class="flex h-2 w-2 rounded-full bg-orange-400 animate-pulse"></span>
                        ⚡ Express Delivery in Metro Manila &middot; Cash on Delivery
                    </div>

                    <!-- Main Heading -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-tight sm:leading-none">
                        Delicious Food, <br class="hidden sm:inline" />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-amber-300 to-purple-400">Delivered to You.</span>
                    </h1>

                    <p class="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                        Craving flame-grilled beef burgers, stone-baked pizzas, or crunchy fried chicken? NovaFood brings chef-crafted meals directly to your door in 30 minutes.
                    </p>

                    <!-- Search Box Form -->
                    <form action="{{ route('menu') }}" method="GET" class="max-w-xl mx-auto lg:mx-0">
                        <div class="relative flex items-center bg-slate-900/90 border border-purple-700/50 rounded-2xl p-2 shadow-xl shadow-purple-950/40 focus-within:border-orange-500 transition-colors">
                            <div class="pl-3 text-slate-400">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search burgers, pepperoni pizza, chicken..." 
                                class="w-full bg-transparent border-0 px-3 py-2 text-sm text-white placeholder-slate-400 focus:outline-none focus:ring-0"
                            />
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-orange-500/30 transition-all duration-200 shrink-0"
                            >
                                Find Food
                            </button>
                        </div>
                    </form>

                    <!-- Trust Stats -->
                    <div class="pt-4 grid grid-cols-3 gap-4 border-t border-purple-900/30 max-w-lg mx-auto lg:mx-0">
                        <div>
                            <div class="text-xl sm:text-2xl font-black text-white">30<span class="text-orange-400">m</span></div>
                            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Fast Delivery</div>
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-black text-white">₱0</div>
                            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Free Over ₱800</div>
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-black text-white">100<span class="text-orange-400">%</span></div>
                            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Fresh & Hot</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Graphic Showcase -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <!-- Glow behind image -->
                        <div class="absolute -inset-1.5 bg-gradient-to-r from-purple-600 to-orange-500 rounded-3xl blur opacity-30 group-hover:opacity-100 transition duration-1000"></div>
                        <div class="relative rounded-3xl overflow-hidden border border-purple-800/50 shadow-2xl bg-slate-900">
                            <img 
                                src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80" 
                                alt="Delicious Flame-Grilled Burger" 
                                class="w-full h-80 sm:h-96 object-cover object-center"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                            
                            <!-- Floating Promo Card -->
                            <div class="absolute bottom-4 left-4 right-4 p-4 rounded-2xl bg-slate-900/90 backdrop-blur-md border border-purple-700/50 flex items-center justify-between shadow-xl">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-orange-400">Featured Today</span>
                                    <h3 class="text-sm font-bold text-white">Classic Smashed Burger</h3>
                                    <span class="text-base font-black text-orange-400">₱99.00</span>
                                </div>
                                <a href="{{ route('menu') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-purple-600 hover:bg-purple-500 shadow-md transition-colors">
                                    Order Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Container Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-20">
        <x-flash-message />

        <!-- Category Filters Section -->
        <section>
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs uppercase font-extrabold tracking-widest text-orange-400 block mb-1">Cuisines</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Explore by Category</h2>
                </div>
                <a href="{{ route('menu') }}" class="text-sm font-bold text-purple-400 hover:text-orange-400 transition-colors flex items-center gap-1">
                    See Full Menu <span>→</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <a 
                        href="{{ route('menu', ['category' => $category->slug]) }}" 
                        class="group flex flex-col items-center text-center p-5 rounded-2xl bg-slate-900/60 border border-purple-900/40 hover:border-orange-500/60 hover:bg-slate-900 shadow-md hover:shadow-orange-500/10 transition-all duration-300 hover:-translate-y-1"
                    >
                        <div class="w-14 h-14 rounded-2xl bg-purple-950/80 group-hover:bg-gradient-to-tr group-hover:from-purple-600 group-hover:to-orange-500 flex items-center justify-center text-2xl mb-3 shadow-inner group-hover:scale-110 transition-all duration-300">
                            {{ $category->icon ?: '🍽️' }}
                        </div>
                        <span class="text-sm font-bold text-white group-hover:text-orange-400 transition-colors">
                            {{ $category->name }}
                        </span>
                        <span class="text-[11px] text-slate-400 mt-0.5">
                            {{ $category->available_foods_count }} items
                        </span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Featured Foods Section -->
        <section id="featured">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs uppercase font-extrabold tracking-widest text-orange-400 block mb-1">Top Rated</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Chef's Featured Delicacies</h2>
                </div>
                <div class="inline-flex rounded-xl bg-slate-900 p-1 border border-purple-900/40 text-xs font-semibold">
                    <span class="px-3 py-1.5 rounded-lg bg-purple-900/60 text-purple-200">Recommended For You</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
                @foreach($featuredFoods as $food)
                    <x-food-card :food="$food" />
                @endforeach
            </div>
        </section>

        <!-- Promotional Banner -->
        <section id="promo" class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-900 via-indigo-950 to-slate-900 border border-purple-800/60 p-8 sm:p-12 shadow-2xl">
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-orange-500/20 blur-[90px] rounded-full pointer-events-none"></div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                <div class="lg:col-span-8 space-y-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-500 text-slate-950 uppercase tracking-wider">
                        Special Promo Deal
                    </span>
                    <h3 class="text-2xl sm:text-4xl font-black text-white tracking-tight">
                        Enjoy Free Express Delivery on Orders Over ₱800!
                    </h3>
                    <p class="text-sm text-purple-200 max-w-xl leading-relaxed">
                        Gather your family and friends. Fill your table with sizzling hot pizzas, loaded burgers, and iced beverages without paying delivery fees.
                    </p>
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-300">
                            <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center">✓</span>
                            No voucher code needed
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-300">
                            <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center">✓</span>
                            Cash on Delivery supported
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 flex justify-start lg:justify-end">
                    <a href="{{ route('menu') }}" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-slate-950 font-black text-sm uppercase tracking-wider shadow-xl shadow-orange-500/30 hover:scale-105 transition-all duration-300">
                        Order Food Now →
                    </a>
                </div>
            </div>
        </section>

        <!-- Popular Meals Section -->
        <section>
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs uppercase font-extrabold tracking-widest text-orange-400 block mb-1">Customer Favorites</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Popular Meals & Combos</h2>
                </div>
                <a href="{{ route('menu') }}" class="text-sm font-bold text-purple-400 hover:text-orange-400 transition-colors flex items-center gap-1">
                    Explore All Foods <span>→</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($popularFoods as $food)
                    <x-food-card :food="$food" />
                @endforeach
            </div>
        </section>
    </div>
@endsection
