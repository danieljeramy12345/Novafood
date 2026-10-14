<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') — NovaFood Administration</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                            950: '#2e1065',
                        },
                        accent: {
                            500: '#f97316',
                            600: '#ea580c',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b0f19;
            color: #f1f5f9;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex flex-col md:flex-row antialiased">
    <!-- Admin Sidebar -->
    <aside class="w-full md:w-64 bg-slate-900 border-r border-purple-950/80 flex flex-col shrink-0">
        <!-- Brand Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-purple-950/60">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-600 to-orange-500 flex items-center justify-center shadow-lg shadow-purple-600/30">
                    <span class="text-xl">🍲</span>
                </div>
                <div>
                    <span class="text-lg font-black tracking-tight text-white block">Nova<span class="text-orange-400">Food</span></span>
                    <span class="text-[10px] font-bold tracking-widest uppercase text-amber-400">Admin Control</span>
                </div>
            </a>
            <button type="button" onclick="document.getElementById('admin-sidebar-menu').classList.toggle('hidden')" class="md:hidden p-2 rounded-lg bg-slate-800 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <div id="admin-sidebar-menu" class="hidden md:flex flex-col flex-1 p-4 space-y-1">
            <span class="px-3 text-[10px] uppercase font-bold tracking-wider text-purple-400/80 py-2">Management</span>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-purple-900/60 text-white border border-purple-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <span class="text-lg">📊</span>
                Dashboard
            </a>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-purple-900/60 text-white border border-purple-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <div class="flex items-center gap-3">
                    <span class="text-lg">📦</span>
                    <span>Orders</span>
                </div>
                @php
                    $pendingOrdersCount = \App\Models\Order::where('order_status', 'pending')->count();
                @endphp
                @if($pendingOrdersCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-slate-950">{{ $pendingOrdersCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.foods.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.foods.*') ? 'bg-purple-900/60 text-white border border-purple-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <span class="text-lg">🍔</span>
                Foods Catalog
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-purple-900/60 text-white border border-purple-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <span class="text-lg">📂</span>
                Categories
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-purple-900/60 text-white border border-purple-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <span class="text-lg">👥</span>
                Users & Customers
            </a>

            <span class="px-3 text-[10px] uppercase font-bold tracking-wider text-purple-400/80 pt-6 pb-2">Quick Navigation</span>

            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:text-orange-400 hover:bg-slate-800/60 transition-colors">
                <span class="text-lg">🌐</span>
                View Live Storefront
            </a>

            <div class="mt-auto pt-6 border-t border-purple-950/60">
                <div class="flex items-center gap-3 px-3 py-2 mb-2 text-xs text-slate-400">
                    <div class="w-8 h-8 rounded-lg bg-purple-700 flex items-center justify-center text-white font-bold uppercase">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="truncate">
                        <div class="font-bold text-white truncate">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-purple-300">Administrator</div>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold text-rose-400 hover:bg-rose-950/40 transition-colors">
                        <span>🚪</span> Log Out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Topbar -->
        <header class="h-20 bg-slate-900/60 border-b border-purple-950/60 px-6 sm:px-8 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-white tracking-tight">@yield('page_title', 'Dashboard')</h1>
                <p class="text-xs text-slate-400">@yield('page_subtitle', 'System administration and live operations')</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-purple-700/50 text-xs font-semibold text-purple-200 hover:bg-purple-950 transition-colors">
                    <span>↗</span> Visit Store
                </a>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-950 border border-emerald-700/50 text-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Live Connected
                </span>
            </div>
        </header>

        <!-- Body Content -->
        <main class="p-6 sm:p-8 flex-1">
            <x-flash-message />
            @yield('content')
        </main>
    </div>
</body>
</html>
