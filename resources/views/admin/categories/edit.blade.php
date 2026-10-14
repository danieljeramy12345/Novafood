@extends('layouts.admin')

@section('title', "Edit {$category->name}")
@section('page_title', 'Edit Category')
@section('page_subtitle', "Update properties for category {$category->name}.")

@section('content')
    <div class="max-w-xl">
        <div class="mb-6">
            <a href="{{ route('admin.categories.index') }}" class="text-xs text-purple-400 hover:text-white transition-colors">
                ← Back to Categories
            </a>
        </div>

        <div class="bg-slate-900 border border-purple-900/40 rounded-3xl p-6 sm:p-8 shadow-xl">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Category Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $category->name) }}" 
                            required 
                            class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500"
                        />
                    </div>

                    <div>
                        <label for="icon" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Icon / Emoji</label>
                        <input 
                            type="text" 
                            id="icon" 
                            name="icon" 
                            value="{{ old('icon', $category->icon) }}" 
                            class="w-full text-center bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-orange-500"
                        />
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Description</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="3" 
                        class="w-full bg-slate-950 border border-purple-800/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 resize-none"
                    >{{ old('description', $category->description) }}</textarea>
                </div>

                <div>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-950 border-purple-800 text-orange-500 focus:ring-orange-500">
                        <span class="text-xs font-bold text-white">Active (Visible on public menu and filters)</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-purple-900/30 flex justify-end gap-3">
                    <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-bold text-xs uppercase tracking-wider hover:bg-slate-700 transition-colors">
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-orange-500/20 hover:scale-105 transition-all"
                    >
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
