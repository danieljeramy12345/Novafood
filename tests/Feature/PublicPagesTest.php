<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully(): void
    {
        $category = Category::create([
            'name' => 'Burgers',
            'slug' => 'burgers',
            'is_active' => true,
        ]);

        Food::create([
            'category_id' => $category->id,
            'name' => 'Classic Burger',
            'slug' => 'classic-burger',
            'description' => 'A juicy beef burger.',
            'price' => 99.00,
            'is_available' => true,
            'is_featured' => true,
            'stock_quantity' => 50,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Delicious Food, Delivered to You');
        $response->assertSee('Classic Burger');
    }

    public function test_food_menu_loads_and_displays_available_foods(): void
    {
        $category = Category::create([
            'name' => 'Pizza',
            'slug' => 'pizza',
            'is_active' => true,
        ]);

        Food::create([
            'category_id' => $category->id,
            'name' => 'Pepperoni Pizza',
            'slug' => 'pepperoni-pizza',
            'description' => 'Cheesy artisan pizza.',
            'price' => 249.00,
            'is_available' => true,
            'stock_quantity' => 20,
        ]);

        $response = $this->get('/menu');

        $response->assertStatus(200);
        $response->assertSee('Pepperoni Pizza');
        $response->assertSee('₱249.00');
    }

    public function test_food_search_filters_results_correctly(): void
    {
        $category = Category::create([
            'name' => 'Chicken',
            'slug' => 'chicken',
            'is_active' => true,
        ]);

        Food::create([
            'category_id' => $category->id,
            'name' => 'Fried Chicken Meal',
            'slug' => 'fried-chicken-meal',
            'description' => 'Crispy fried chicken with rice.',
            'price' => 129.00,
            'is_available' => true,
            'stock_quantity' => 30,
        ]);

        Food::create([
            'category_id' => $category->id,
            'name' => 'Chocolate Fudge Cake',
            'slug' => 'chocolate-fudge-cake',
            'description' => 'Sweet dessert slice.',
            'price' => 89.00,
            'is_available' => true,
            'stock_quantity' => 15,
        ]);

        $response = $this->get('/menu?search=Chicken');

        $response->assertStatus(200);
        $response->assertSee('Fried Chicken Meal');
        $response->assertDontSee('Chocolate Fudge Cake');
    }

    public function test_food_details_page_loads_with_correct_info(): void
    {
        $category = Category::create([
            'name' => 'Drinks',
            'slug' => 'drinks',
            'is_active' => true,
        ]);

        $food = Food::create([
            'category_id' => $category->id,
            'name' => 'Signature Iced Tea',
            'slug' => 'signature-iced-tea',
            'description' => 'Cold calamansi honey iced tea.',
            'price' => 39.00,
            'is_available' => true,
            'stock_quantity' => 50,
        ]);

        $response = $this->get('/menu/' . $food->slug);

        $response->assertStatus(200);
        $response->assertSee('Signature Iced Tea');
        $response->assertSee('₱39.00');
        $response->assertSee('Add to Cart');
    }
}
