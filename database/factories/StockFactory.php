<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(), // Assuming you have a Product factory
            'quantity' => $this->faker->numberBetween(1, 1000),
            'reserved_quantity' => $this->faker->numberBetween(0, 500),
        ];
    }
}
