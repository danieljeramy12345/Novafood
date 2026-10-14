<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FoodController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $query = Food::where('is_available', true)->with('category');

        // Search by food name or description
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category slug or id
        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->latest(),
        };

        $foods = $query->paginate(12)->withQueryString();

        return view('foods.index', compact('foods', 'categories'));
    }

    public function show(Food $food): View
    {
        $food->load('category');

        $relatedFoods = Food::where('category_id', $food->category_id)
            ->where('id', '!=', $food->id)
            ->where('is_available', true)
            ->take(4)
            ->get();

        return view('foods.show', compact('food', 'relatedFoods'));
    }
}
