<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ECommerceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()
            ->count(5)
            ->has(
                Product::factory()
                    ->count(10)
                    ->has(Stock::factory())
                    ->has(StockMovement::factory()->count(3))
            )
            ->create();

        User::factory()
            ->count(10)
            ->has(
                Order::factory()
                    ->count(5)
                    ->hasAttached(
                        Product::inRandomOrder()->take(3)->get(),
                        fn () => [
                            'quantity' => fake()->numberBetween(1, 5),
                            'unit_price' => fake()->randomFloat(2, 10, 100),
                            'total_price' => fake()->randomFloat(2, 50, 500),
                        ]
                    )
            )
            ->create();
    }
}
