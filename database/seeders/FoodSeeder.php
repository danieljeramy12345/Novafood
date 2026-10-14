<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        $foods = [
            // Burgers
            [
                'category' => 'Burgers',
                'name' => 'Classic Burger',
                'slug' => 'classic-burger',
                'description' => '100% pure beef patty grilled to perfection with crisp lettuce, ripe tomatoes, onions, and house special sauce in a toasted sesame bun.',
                'price' => 99.00,
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => true,
                'stock_quantity' => 120,
            ],
            [
                'category' => 'Burgers',
                'name' => 'Cheeseburger Deluxe',
                'slug' => 'cheeseburger-deluxe',
                'description' => 'Juicy flame-grilled beef topped with melted sharp cheddar cheese, caramelized onions, pickles, and creamy garlic mayonnaise.',
                'price' => 129.00,
                'image' => 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => true,
                'stock_quantity' => 90,
            ],
            [
                'category' => 'Burgers',
                'name' => 'Double Bacon BBQ Burger',
                'slug' => 'double-bacon-bbq-burger',
                'description' => 'Two smashed beef patties layered with crispy smoked bacon, double American cheese, and rich smoky barbecue glaze.',
                'price' => 189.00,
                'image' => 'https://images.unsplash.com/photo-1553979459-d2229ba7433b?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => false,
                'stock_quantity' => 75,
            ],

            // Pizza
            [
                'category' => 'Pizza',
                'name' => 'Pepperoni Pizza',
                'slug' => 'pepperoni-pizza',
                'description' => 'Hand-tossed crust covered with rich San Marzano tomato sauce, generous sliced pepperoni, and bubbly mozzarella cheese.',
                'price' => 249.00,
                'image' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => true,
                'stock_quantity' => 50,
            ],
            [
                'category' => 'Pizza',
                'name' => 'Four Cheese Supreme',
                'slug' => 'four-cheese-supreme',
                'description' => 'Decadent blend of mozzarella, aged parmesan, creamy gorgonzola, and ricotta on garlic-infused herb crust.',
                'price' => 279.00,
                'image' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => false,
                'stock_quantity' => 40,
            ],

            // Chicken
            [
                'category' => 'Chicken',
                'name' => 'Fried Chicken Meal',
                'slug' => 'fried-chicken-meal',
                'description' => 'Two pieces of extra crispy, deeply seasoned golden fried chicken served with rich country gravy and steamed jasmine rice.',
                'price' => 129.00,
                'image' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => true,
                'stock_quantity' => 100,
            ],
            [
                'category' => 'Chicken',
                'name' => 'Spicy Glazed Wings (6pcs)',
                'slug' => 'spicy-glazed-wings',
                'description' => 'Tender chicken wings tossed in our sweet & fiery chili-garlic glaze, sprinkled with toasted sesame seeds and scallions.',
                'price' => 169.00,
                'image' => 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => false,
                'stock_quantity' => 80,
            ],

            // Rice Meals
            [
                'category' => 'Rice Meals',
                'name' => 'Chicken Rice Bowl',
                'slug' => 'chicken-rice-bowl',
                'description' => 'Sliced marinated teriyaki chicken breast served over warm fragrant rice with steamed broccoli, carrots, and sweet glaze.',
                'price' => 119.00,
                'image' => 'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => true,
                'stock_quantity' => 110,
            ],
            [
                'category' => 'Rice Meals',
                'name' => 'Beef Bulgogi Rice Meal',
                'slug' => 'beef-bulgogi-rice-meal',
                'description' => 'Thinly sliced tender marinated beef ribeye sauteed with sweet onions and mushrooms, served with garlic rice and fried egg.',
                'price' => 159.00,
                'image' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => false,
                'stock_quantity' => 65,
            ],

            // Sides & Snacks
            [
                'category' => 'Sides & Snacks',
                'name' => 'Crispy French Fries',
                'slug' => 'crispy-french-fries',
                'description' => 'Golden brown Idaho potato fries, crispy on the outside and fluffy inside, dusted with sea salt and garlic herb seasoning.',
                'price' => 59.00,
                'image' => 'https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => false,
                'stock_quantity' => 200,
            ],
            [
                'category' => 'Sides & Snacks',
                'name' => 'Loaded Cheese Fries',
                'slug' => 'loaded-cheese-fries',
                'description' => 'Crinkle-cut fries smothered in hot melted cheddar sauce, bacon bits, and chopped jalapeño peppers.',
                'price' => 89.00,
                'image' => 'https://images.unsplash.com/photo-1585109649139-366815a0d713?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => false,
                'stock_quantity' => 95,
            ],

            // Drinks
            [
                'category' => 'Drinks',
                'name' => 'Signature Iced Tea',
                'slug' => 'signature-iced-tea',
                'description' => 'Slow-brewed black tea infused with natural calamansi citrus and honey, served ice-cold with fresh mint leaves.',
                'price' => 39.00,
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => false,
                'stock_quantity' => 250,
            ],
            [
                'category' => 'Drinks',
                'name' => 'Mango Passion Fruit Cooler',
                'slug' => 'mango-passion-cooler',
                'description' => 'Refreshing tropical blend of sweet Philippine mangoes and tangy passion fruit pulp over crushed ice.',
                'price' => 69.00,
                'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => false,
                'stock_quantity' => 140,
            ],

            // Desserts
            [
                'category' => 'Desserts',
                'name' => 'Chocolate Fudge Cake',
                'slug' => 'chocolate-fudge-cake',
                'description' => 'Rich, moist dark chocolate cake layered with silky Belgian chocolate ganache and chocolate shavings.',
                'price' => 89.00,
                'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => true,
                'stock_quantity' => 60,
            ],
            [
                'category' => 'Desserts',
                'name' => 'Strawberry Cheesecake',
                'slug' => 'strawberry-cheesecake',
                'description' => 'Velvety New York style baked cheesecake on buttery graham crust, topped with sweet strawberry compote.',
                'price' => 109.00,
                'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_featured' => false,
                'stock_quantity' => 45,
            ],
        ];

        foreach ($foods as $item) {
            $cat = $categories->get($item['category']);
            if (! $cat) {
                continue;
            }

            Food::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $cat->id,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'image' => $item['image'],
                    'is_available' => $item['is_available'],
                    'is_featured' => $item['is_featured'],
                    'stock_quantity' => $item['stock_quantity'],
                ]
            );
        }
    }
}
