<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

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
