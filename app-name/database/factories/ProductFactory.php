<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name'         => fake()->words(2, true),     // 例: "Smart Pen"
            'price'        => fake()->numberBetween(100, 20000),
            'description'  => fake()->optional(0.6)->paragraph(),
            'published_at' => fake()->optional(0.8)->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
