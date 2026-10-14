<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Burgers',
                'description' => 'Juicy handcrafted flame-grilled beef and crispy chicken burgers served with premium sauces.',
                'icon' => '🍔',
                'is_active' => true,
            ],
            [
                'name' => 'Pizza',
                'description' => 'Stone-baked artisan pizzas topped with premium cheeses and fresh herbs.',
                'icon' => '🍕',
                'is_active' => true,
            ],
            [
                'name' => 'Chicken',
                'description' => 'Crispy golden fried chicken marinated in signature herbs and spices.',
                'icon' => '🍗',
                'is_active' => true,
            ],
            [
                'name' => 'Rice Meals',
                'description' => 'Satisfying hot rice plates paired with savory mains and Filipino comfort favorites.',
                'icon' => '🍚',
                'is_active' => true,
            ],
            [
                'name' => 'Sides & Snacks',
                'description' => 'Crispy seasoned fries, onion rings, and savory finger bites.',
                'icon' => '🍟',
                'is_active' => true,
            ],
            [
                'name' => 'Drinks',
                'description' => 'Refreshing iced teas, fruit coolers, cold brews, and sodas.',
                'icon' => '🥤',
                'is_active' => true,
            ],
            [
                'name' => 'Desserts',
                'description' => 'Decadent chocolate cakes, creamy pastries, and sweet treats.',
                'icon' => '🍰',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'icon' => $data['icon'],
                    'is_active' => $data['is_active'],
                ]
            );
        }
    }
}
