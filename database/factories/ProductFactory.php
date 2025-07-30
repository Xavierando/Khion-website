<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
            'code' => fake()->word(),
            'name' => array_reduce(fake()->words(2) , fn ($c,$i) => $c. ' '.$i),
            'description' => fake()->text(600),
            'base_price' => fake()->numberBetween(100,900),
        ];
    }
}
