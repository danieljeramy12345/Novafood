@extends('layouts.app')

@section('title', 'Food Menu — NovaFood Delivery')
@section('meta_description', 'Explore NovaFood fresh food menu: flame-grilled burgers, cheesy pizzas, fried chicken, rice bowls, and refreshments.')

@section('content')
    <!-- Page Header & Filter Bar -->
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <span class="text-xs uppercase font-extrabold tracking-widest text-orange-400 block mb-1">Our Complete Catalog</span>
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">The NovaFood Menu</h1>
                <p class="text-sm text-slate-400 mt-1">Discover fresh flavors prepared on order and delivered piping hot.</p>
            </div>

            <!-- Sort Form -->
            <form action="{{ route('menu') }}" method="GET" class="flex items-center gap-3">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <label for="sort" class="text-xs font-semibold text-slate-400 uppercase tracking-wider shrink-0">Sort By:</label>
                <select 
                    name="sort" 
                    id="sort" 
                    onchange="this.form.submit()" 
                    class="bg-slate-900 border border-purple-800/60 rounded-xl px-3 py-2 text-xs font-semibold text-white focus:outline-none focus:border-orange-500 cursor-pointer"
                >
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest Items</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                </select>
            </form>
        </div>

        <!-- Search & Category Filter Pills -->
        <div class="space-y-4">
            <!-- Search Input -->
            <form action="{{ route('menu') }}" method="GET" class="max-w-2xl">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                <div class="relative flex items-center bg-slate-900 border border-purple-800/50 rounded-2xl p-1.5 focus-within:border-orange-500 shadow-lg">
                    <div class="pl-3 text-purple-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search food by name or ingredients..." 
                        class="w-full bg-transparent border-0 px-3 py-2 text-sm text-white placeholder-slate-400 focus:outline-none"
                    />
                    @if(request('search'))
                        <a href="{{ route('menu', array_filter(['category' => request('category'), 'sort' => request('sort')])) }}" class="px-2 text-xs text-slate-400 hover:text-white">Clear</a>
                    @endif
                    <button type="submit" class="px-5 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-slate-950 font-bold text-xs uppercase tracking-wider transition-colors shrink-0">
                        Filter
                    </button>
                </div>
            </form>

            <!-- Category Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <a 
                    href="{{ route('menu', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ !request('category') ? 'bg-orange-500 text-slate-950 shadow-md shadow-orange-500/20' : 'bg-slate-900 text-slate-300 hover:text-white border border-purple-900/50' }}"
                >
                    All Cuisines
                </a>

                @foreach($categories as $category)
                    <a 
                        href="{{ route('menu', array_filter(['category' => $category->slug, 'search' => request('search'), 'sort' => request('sort')])) }}" 
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('category') == $category->slug ? 'bg-orange-500 text-slate-950 shadow-md shadow-orange-500/20' : 'bg-slate-900 text-slate-300 hover:text-white border border-purple-900/50' }}"
                    >
                        <span>{{ $category->icon ?: '🍽️' }}</span>
                        <span>{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Food Items Grid -->
    @if($foods->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            @foreach($foods as $food)
                <x-food-card :food="$food" />
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $foods->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="rounded-3xl bg-slate-900/60 border border-purple-900/40 p-12 text-center max-w-xl mx-auto my-12">
            <div class="w-20 h-20 rounded-full bg-purple-950/80 border border-purple-800/50 flex items-center justify-center text-4xl mx-auto mb-4">
                🔍
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No food items found</h3>
            <p class="text-sm text-slate-400 mb-6">
                We couldn't find any meals matching <span class="text-orange-400 font-semibold">"{{ request('search') ?: request('category') }}"</span>. Try clearing your filters or search for something else!
            </p>
            <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition-colors">
                View All Menu Items
            </a>
        </div>
    @endif
@endsection
