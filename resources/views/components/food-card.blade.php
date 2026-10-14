@props(['food'])

<div class="group relative flex flex-col bg-slate-900/90 rounded-3xl overflow-hidden border border-purple-900/40 hover:border-purple-600/70 shadow-lg shadow-purple-950/20 hover:shadow-2xl hover:shadow-purple-700/20 transition-all duration-300 hover:-translate-y-1">
    <!-- Image & Badge Container -->
    <a href="{{ route('foods.show', $food->slug) }}" class="relative h-48 sm:h-52 w-full overflow-hidden block bg-slate-950">
        <img 
            src="{{ $food->image_url }}" 
            alt="{{ $food->name }}" 
            loading="lazy"
            class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-60"></div>
        
        <!-- Category Badge -->
        <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase bg-slate-950/80 backdrop-blur-md text-purple-300 border border-purple-700/40 shadow-sm">
            {{ $food->category->icon ?? '🍽️' }} {{ $food->category->name }}
        </span>

        @if($food->is_featured)
            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-md">
                ★ Best Seller
            </span>
        @endif

        @if(! $food->is_available || $food->stock_quantity <= 0)
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center">
                <span class="px-4 py-2 rounded-xl bg-rose-600/90 text-white font-bold text-xs uppercase tracking-wider shadow-lg">
                    Sold Out
                </span>
            </div>
        @endif
    </a>

    <!-- Card Content -->
    <div class="flex-1 p-5 flex flex-col justify-between">
        <div>
            <div class="flex items-baseline justify-between gap-2 mb-1.5">
                <a href="{{ route('foods.show', $food->slug) }}" class="text-base font-bold text-white group-hover:text-orange-400 transition-colors line-clamp-1">
                    {{ $food->name }}
                </a>
            </div>

            <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed mb-4">
                {{ $food->description }}
            </p>
        </div>

        <div class="pt-3 border-t border-purple-900/30 flex items-center justify-between gap-3">
            <div>
                <span class="text-[10px] uppercase font-semibold text-slate-400 block -mb-0.5">Price</span>
                <span class="text-lg font-black text-orange-400 tracking-tight">
                    {{ $food->formatted_price }}
                </span>
            </div>

            @if($food->is_available && $food->stock_quantity > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="food_id" value="{{ $food->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button 
                        type="submit" 
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-purple-600 to-orange-500 hover:from-purple-500 hover:to-orange-400 shadow-md shadow-purple-600/30 hover:shadow-orange-500/40 active:scale-95 transition-all duration-200"
                        title="Add 1 to Cart"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add to Cart
                    </button>
                </form>
            @else
                <button disabled class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-500 bg-slate-800/80 cursor-not-allowed">
                    Unavailable
                </button>
            @endif
        </div>
    </div>
</div>
