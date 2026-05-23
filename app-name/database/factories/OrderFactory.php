<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => \App\Models\User::factory(),
            'order_date'  => fake()->dateTimeBetween('-180 days', 'today'),
            'total_amount'=> null, // あとで集計して入れる or null のまま
        ];
    }
}
