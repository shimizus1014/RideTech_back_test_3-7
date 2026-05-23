<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id'   => \App\Models\Order::factory(),
            'product_id' => \App\Models\Product::factory(), // 既存Productを使うなら後で上書き
            'qty'        => fake()->numberBetween(1, 5),
            'unit_price' => fake()->numberBetween(100, 20000),
        ];
    }
}
