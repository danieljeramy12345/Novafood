<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FoodController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $query = Food::with('category')->latest();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($request->filled('is_available')) {
            $query->where('is_available', $request->boolean('is_available'));
        }

        $foods = $query->paginate(15)->withQueryString();

        return view('admin.foods.index', compact('foods', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.foods.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'image_url' => ['nullable', 'url', 'max:500'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('foods', 'public');
        } elseif (! empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        Food::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(4),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock_quantity' => $validated['stock_quantity'],
            'is_available' => $request->boolean('is_available', true),
            'is_featured' => $request->boolean('is_featured', false),
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.foods.index')
            ->with('success', "Food item \"{$validated['name']}\" added successfully.");
    }

    public function edit(Food $food): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.foods.edit', compact('food', 'categories'));
    }

    public function update(Request $request, Food $food): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'image_url' => ['nullable', 'url', 'max:500'],
        ]);

        $imagePath = $food->image;
        if ($request->hasFile('image')) {
            if ($food->image && ! Str::startsWith($food->image, ['http://', 'https://'])) {
                Storage::disk('public')->delete($food->image);
            }
            $imagePath = $request->file('image')->store('foods', 'public');
        } elseif (! empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        $food->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock_quantity' => $validated['stock_quantity'],
            'is_available' => $request->boolean('is_available'),
            'is_featured' => $request->boolean('is_featured'),
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.foods.index')
            ->with('success', "Food item \"{$food->name}\" updated successfully.");
    }

    public function destroy(Food $food): RedirectResponse
    {
        // Safe soft-deletion protects historical orders from broken relationships
        $foodName = $food->name;
        $food->delete();

        return redirect()->route('admin.foods.index')
            ->with('success', "Food \"{$foodName}\" deactivated and soft-deleted safely.");
    }

    public function toggleAvailability(Food $food): RedirectResponse
    {
        $food->update(['is_available' => ! $food->is_available]);

        $status = $food->is_available ? 'available' : 'unavailable';
        return back()->with('success', "\"{$food->name}\" is now marked as {$status}.");
    }
}
