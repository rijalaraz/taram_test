<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $filename = 'products/' . fake()->uuid() . '.png';

        // Storage::disk('public')->put(
        //     $filename,
        //     file_get_contents('https://picsum.photos/300')
        // );

        return [
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'image_url' => '',
            'is_active' => $this->faker->boolean(70), // 70% chance of being active
            'category_id' => \App\Models\Category::factory(), // Assuming you have a Category factory
        ];
    }
}
