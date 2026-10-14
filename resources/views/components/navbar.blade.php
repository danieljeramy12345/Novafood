@php
    $cartService = app(\App\Services\CartService::class);
    $cartTotals = $cartService->calculateTotals();
    $cartCount = $cartTotals['item_count'];
@endphp

<header class="sticky top-0 z-50 bg-slate-950/85 backdrop-blur-md border-b border-purple-900/40 text-white transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-purple-600 to-orange-500 flex items-center justify-center shadow-lg shadow-purple-600/30 group-hover:scale-105 transition-transform duration-300">
                    <span class="text-2xl" role="img" aria-label="NovaFood Logo">🍲</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-black tracking-tight text-white flex items-center">
                        Nova<span class="text-orange-400">Food</span>
                    </span>
                    <span class="text-[10px] uppercase font-bold tracking-widest text-purple-300/80 -mt-1">Express Delivery</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 font-medium text-sm">
                <a href="{{ route('home') }}" class="transition-colors hover:text-orange-400 {{ request()->routeIs('home') ? 'text-orange-400 font-semibold' : 'text-slate-300' }}">
                    Home
                </a>
                <a href="{{ route('menu') }}" class="transition-colors hover:text-orange-400 {{ request()->routeIs('menu*') || request()->routeIs('foods.*') ? 'text-orange-400 font-semibold' : 'text-slate-300' }}">
                    Our Menu
                </a>
                <a href="{{ route('home') }}#featured" class="transition-colors hover:text-orange-400 text-slate-300">
                    Popular
                </a>
                <a href="{{ route('home') }}#promo" class="transition-colors hover:text-orange-400 text-slate-300">
                    Deals
                </a>
            </nav>

            <!-- Actions & Auth -->
            <div class="flex items-center gap-4">
                <!-- Cart Button -->
                <a href="{{ route('cart.index') }}" class="relative inline-flex items-center justify-center p-2.5 rounded-xl bg-purple-950/60 hover:bg-purple-900/80 border border-purple-800/50 text-white transition-all duration-200 group" aria-label="Shopping Cart">
                    <svg class="w-6 h-6 text-purple-200 group-hover:text-orange-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 px-2 py-0.5 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 rounded-full shadow-md animate-pulse">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <!-- User Dropdown Menu -->
                    <div class="relative group">
                        <button type="button" class="flex items-center gap-3 p-1.5 pl-3 pr-2 rounded-xl bg-purple-900/30 hover:bg-purple-900/60 border border-purple-700/40 text-sm font-semibold transition-all">
                            <span class="text-purple-200 truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs uppercase shadow">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>

                        <div class="absolute right-0 mt-2 w-56 rounded-2xl bg-slate-900 border border-purple-800/40 shadow-2xl py-2 hidden group-hover:block transition-all duration-200 z-50">
                            <div class="px-4 py-2 border-b border-purple-900/30 text-xs text-slate-400">
                                Logged in as <span class="font-bold text-purple-300 block truncate">{{ Auth::user()->email }}</span>
                            </div>

                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-amber-300 hover:bg-purple-950 font-medium">
                                    <span>⚙️</span> Admin Dashboard
                                </a>
                            @endif

                            <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-200 hover:bg-purple-950">
                                <span>📊</span> Dashboard
                            </a>
                            <a href="{{ route('customer.orders') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-200 hover:bg-purple-950">
                                <span>🛍️</span> My Orders
                            </a>
                            <a href="{{ route('customer.profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-200 hover:bg-purple-950">
                                <span>👤</span> Profile Settings
                            </a>

                            <div class="border-t border-purple-900/30 my-1"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-rose-400 hover:bg-rose-950/40">
                                    <span>🚪</span> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="hidden sm:flex items-center gap-3">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-300 hover:text-white transition-colors">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-purple-600 via-purple-700 to-orange-500 hover:from-purple-500 hover:to-orange-400 shadow-md shadow-purple-600/20 hover:shadow-orange-500/30 transition-all duration-200">
                            Register
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Toggle Button -->
                <button type="button" onclick="document.getElementById('mobile-nav').classList.toggle('hidden')" class="md:hidden p-2 rounded-xl bg-purple-950/50 border border-purple-800/40 text-slate-300 hover:text-white" aria-label="Toggle Navigation">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div id="mobile-nav" class="hidden md:hidden pb-6 pt-2 border-t border-purple-900/40">
            <div class="flex flex-col gap-3 font-medium text-sm pt-2">
                <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg hover:bg-purple-900/40 text-slate-200">Home</a>
                <a href="{{ route('menu') }}" class="px-3 py-2 rounded-lg hover:bg-purple-900/40 text-slate-200">Our Menu</a>
                <a href="{{ route('cart.index') }}" class="px-3 py-2 rounded-lg hover:bg-purple-900/40 text-slate-200 flex justify-between">
                    <span>Cart</span>
                    <span class="px-2 py-0.5 rounded-full bg-orange-500 text-xs font-bold text-white">{{ $cartCount }}</span>
                </a>
                @auth
                    <a href="{{ route('customer.dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-purple-900/40 text-slate-200">Dashboard</a>
                    <a href="{{ route('customer.orders') }}" class="px-3 py-2 rounded-lg hover:bg-purple-900/40 text-slate-200">My Orders</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-purple-900/40 text-amber-300 font-semibold">Admin Panel</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-rose-400 hover:bg-rose-950/40">Log Out</button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-2 pt-3 border-t border-purple-900/40">
                        <a href="{{ route('login') }}" class="text-center py-2.5 rounded-xl border border-purple-700/50 text-slate-200 font-semibold text-xs">Sign In</a>
                        <a href="{{ route('register') }}" class="text-center py-2.5 rounded-xl bg-orange-500 text-white font-bold text-xs">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
