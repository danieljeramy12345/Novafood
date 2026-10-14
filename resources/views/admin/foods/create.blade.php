@extends('layouts.admin')

@section('title', 'Add Food Item')
@section('page_title', 'Add New Food Product')
@section('page_subtitle', 'Create a new menu item in the database catalog.')

@section('content')
    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.foods.index') }}" class="text-xs text-purple-400 hover:text-white transition-colors">
                ← Back to Food Products
            </a>
        </div>

        <div class="bg-slate-900 border border-purple-900/40 rounded-3xl p-6 sm:p-10 shadow-xl">
            <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Food Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Food Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            placeholder="e.g. Sizzling Sisig Rice Bowl"
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                        />
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Category *</label>
                        <select 
                            id="category_id" 
                            name="category_id" 
                            required 
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-orange-500 cursor-pointer"
                        >
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon ?: '🍽️' }} {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Description & Ingredients *</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="3" 
                        required 
                        placeholder="Detailed flavor notes, ingredients, and portion details..."
                        class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 resize-none"
                    >{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Price in PHP -->
                    <div>
                        <label for="price" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Price in Philippine Pesos (₱) *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-orange-400 font-bold">₱</span>
                            <input 
                                type="number" 
                                id="price" 
                                name="price" 
                                value="{{ old('price', '99.00') }}" 
                                step="0.01" 
                                min="0" 
                                required 
                                class="w-full bg-slate-950 border border-purple-800/60 rounded-xl pl-8 pr-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 font-bold"
                            />
                        </div>
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label for="stock_quantity" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Stock Inventory *</label>
                        <input 
                            type="number" 
                            id="stock_quantity" 
                            name="stock_quantity" 
                            value="{{ old('stock_quantity', '100') }}" 
                            min="0" 
                            required 
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                        />
                    </div>
                </div>

                <!-- Food Photography / Image Upload -->
                <div class="p-5 rounded-2xl bg-slate-950/60 border border-purple-900/40 space-y-4">
                    <h4 class="text-xs font-bold text-purple-300 uppercase tracking-wider">Product Photography</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="image" class="block text-xs text-slate-400 mb-1">Upload Image File (JPEG, PNG, WebP up to 3MB)</label>
                            <input 
                                type="file" 
                                id="image" 
                                name="image" 
                                accept="image/*"
                                class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-900/60 file:text-purple-200 hover:file:bg-purple-800"
                            />
                        </div>

                        <div>
                            <label for="image_url" class="block text-xs text-slate-400 mb-1">Or Photo URL (e.g. Unsplash URL)</label>
                            <input 
                                type="url" 
                                id="image_url" 
                                name="image_url" 
                                value="{{ old('image_url') }}" 
                                placeholder="https://images.unsplash.com/..."
                                class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-orange-500"
                            />
                        </div>
                    </div>
                </div>

                <!-- Status Checkboxes -->
                <div class="flex flex-wrap items-center gap-6 pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', 1) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-950 border-purple-800 text-orange-500 focus:ring-orange-500">
                        <span class="text-xs font-bold text-white">Item is Available for Ordering</span>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-950 border-purple-800 text-orange-500 focus:ring-orange-500">
                        <span class="text-xs font-bold text-white">Mark as Chef's Featured Item</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-purple-900/30 flex justify-end gap-3">
                    <a href="{{ route('admin.foods.index') }}" class="px-5 py-3 rounded-xl bg-slate-800 text-slate-300 font-bold text-xs uppercase tracking-wider hover:bg-slate-700 transition-colors">
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="px-8 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-orange-500/20 hover:scale-105 transition-all"
                    >
                        Save Food Product
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
