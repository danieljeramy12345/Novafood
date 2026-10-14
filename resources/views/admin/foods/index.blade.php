@extends('layouts.admin')

@section('title', 'Food Products Catalog')
@section('page_title', 'Food Products Catalog')
@section('page_subtitle', 'Manage menu items, prices, stock levels, and food availability.')

@section('content')
    <div class="space-y-6">
        <!-- Action & Search Bar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            <!-- Search & Filters -->
            <form action="{{ route('admin.foods.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search food by name..." 
                    class="bg-slate-900 border border-purple-800/60 rounded-xl px-4 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 w-full sm:w-60"
                />

                <select 
                    name="category_id" 
                    onchange="this.form.submit()" 
                    class="bg-slate-900 border border-purple-800/60 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-orange-500 cursor-pointer"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-purple-700 hover:bg-purple-600 text-white font-bold text-xs">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'category_id']))
                    <a href="{{ route('admin.foods.index') }}" class="text-xs text-slate-400 hover:text-white">Reset</a>
                @endif
            </form>

            <a href="{{ route('admin.foods.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-lg shadow-orange-500/20 hover:scale-105 transition-all shrink-0">
                <span>➕</span> Add New Food
            </a>
        </div>

        <!-- Foods Table -->
        <div class="bg-slate-900 border border-purple-900/40 rounded-3xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="text-slate-400 uppercase tracking-wider border-b border-purple-900/40 bg-slate-950/40">
                        <tr>
                            <th class="py-3.5 px-4">Item</th>
                            <th class="py-3.5 px-4">Category</th>
                            <th class="py-3.5 px-4">Price</th>
                            <th class="py-3.5 px-4">Stock</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-900/20">
                        @forelse($foods as $food)
                            <tr class="hover:bg-purple-950/20 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <img 
                                            src="{{ $food->image_url }}" 
                                            alt="{{ $food->name }}" 
                                            class="w-12 h-12 rounded-xl object-cover bg-slate-950 shrink-0 border border-purple-900/40"
                                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';"
                                        />
                                        <div>
                                            <span class="font-bold text-white text-sm block">{{ $food->name }}</span>
                                            @if($food->is_featured)
                                                <span class="text-[10px] text-amber-400 font-semibold">★ Featured</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-lg bg-purple-950/80 border border-purple-900/50 text-[11px] font-semibold text-purple-300">
                                        {{ $food->category->name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-black text-orange-400 text-sm">
                                    ₱{{ number_format((float) $food->price, 2) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold {{ $food->stock_quantity <= 10 ? 'text-amber-400' : 'text-slate-300' }}">
                                        {{ $food->stock_quantity }} units
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <form action="{{ route('admin.foods.toggle', $food) }}" method="POST">
                                        @csrf
                                        <button 
                                            type="submit" 
                                            class="px-2.5 py-1 rounded-full text-[11px] font-bold border transition-colors {{ $food->is_available ? 'bg-emerald-950/80 text-emerald-300 border-emerald-800 hover:bg-emerald-900' : 'bg-rose-950/80 text-rose-300 border-rose-800 hover:bg-rose-900' }}"
                                            title="Click to toggle availability"
                                        >
                                            {{ $food->is_available ? 'Available' : 'Unavailable' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.foods.edit', $food) }}" class="p-2 rounded-lg bg-slate-800 hover:bg-purple-900 text-slate-300 hover:text-white transition-colors" title="Edit Item">
                                            ✏️
                                        </a>

                                        <form action="{{ route('admin.foods.destroy', $food) }}" method="POST" onsubmit="return confirm('Soft-delete this food item? Historical orders referencing it will remain intact.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-slate-800 hover:bg-rose-950 text-slate-300 hover:text-rose-400 transition-colors" title="Deactivate & Soft Delete">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    No food products found matching your search.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($foods->hasPages())
                <div class="p-4 border-t border-purple-900/30">
                    {{ $foods->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
