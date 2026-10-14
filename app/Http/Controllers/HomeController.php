<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::where('is_active', true)->withCount('availableFoods')->get();

        $featuredFoods = Food::where('is_available', true)
            ->where('is_featured', true)
            ->with('category')
            ->take(6)
            ->get();

        $popularFoods = Food::where('is_available', true)
            ->with('category')
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('home', compact('categories', 'featuredFoods', 'popularFoods'));
    }
}
