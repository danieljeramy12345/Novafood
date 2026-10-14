<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('foods')->orderBy('name')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?: '🍽️',
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category \"{$validated['name']}\" created successfully.");
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?: $category->icon,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category \"{$category->name}\" updated successfully.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        // Safety constraint check: prevent deletion of categories containing food products
        $foodCount = $category->foods()->count();
        if ($foodCount > 0) {
            return back()->with('error', "Cannot delete category \"{$category->name}\" because it currently contains {$foodCount} food products. Please reassign or delete the products first.");
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Category \"{$categoryName}\" deleted successfully.");
    }
}
