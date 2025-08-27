<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
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
            'total' => fake()->numberBetween(100, 900),
            'status' => fake()->randomElement(OrderStatus::cases()),
            'stripe_checkout_id' => '',
            'user_id' => 2,
            'cart_id' => Cart::inRandomOrder()->first(),
        ];
    }
}
