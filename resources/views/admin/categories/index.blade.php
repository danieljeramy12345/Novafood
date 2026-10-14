@extends('layouts.admin')

@section('title', 'Food Categories')
@section('page_title', 'Menu Categories')
@section('page_subtitle', 'Organize your dishes into browsable cuisines and food types.')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">All Registered Categories ({{ $categories->total() }})</span>

            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-lg shadow-orange-500/20 hover:scale-105 transition-all">
                <span>➕</span> Add Category
            </a>
        </div>

        <div class="bg-slate-900 border border-purple-900/40 rounded-3xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="text-slate-400 uppercase tracking-wider border-b border-purple-900/40 bg-slate-950/40">
                        <tr>
                            <th class="py-3.5 px-4">Category</th>
                            <th class="py-3.5 px-4">Slug</th>
                            <th class="py-3.5 px-4">Description</th>
                            <th class="py-3.5 px-4">Total Foods</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-900/20">
                        @forelse($categories as $category)
                            <tr class="hover:bg-purple-950/20 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl w-10 h-10 rounded-xl bg-purple-950 flex items-center justify-center border border-purple-900/60">
                                            {{ $category->icon ?: '🍽️' }}
                                        </span>
                                        <span class="font-bold text-white text-sm">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-purple-300 text-[11px]">
                                    {{ $category->slug }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-400 max-w-xs truncate">
                                    {{ $category->description ?? 'No description provided.' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300">
                                        {{ $category->foods_count }} items
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $category->is_active ? 'bg-emerald-950/80 text-emerald-300 border-emerald-800' : 'bg-slate-800 text-slate-400 border-slate-700' }}">
                                        {{ $category->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="p-2 rounded-lg bg-slate-800 hover:bg-purple-900 text-slate-300 hover:text-white transition-colors" title="Edit Category">
                                            ✏️
                                        </a>

                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category? Category must not contain any dishes.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-slate-800 hover:bg-rose-950 text-slate-300 hover:text-rose-400 transition-colors" title="Delete Category">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-400">
                                    No categories have been created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-purple-900/30">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
