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

        $json = json_decode('{"options":[{"type":"select","name":"size","options":[{"name":"150 cm","price":0},{"name":"160 cm","price":0},{"name":"170 cm","price":0}]},{"type":"select","name":"hardness","options":[{"name":"flexible","price":0},{"name":"normal","price":0},{"name":"stiff","price":0}]}]}');

        return [
            'code' => fake()->word(),
            'name' => array_reduce(fake()->words(2), fn ($c, $i) => $c.' '.$i),
            'description' => fake()->text(600),
            'base_price' => fake()->numberBetween(100, 900),
            'quantity' => fake()->numberBetween(1, 5),
            'configuration' => $json,
            'stripe_price_id' => fake()->randomElement(['price_1Rt5ZiRxtEVoFjKik3t2Ugrx', 'price_1RtSV7RxtEVoFjKihAPunzf2', 'price_1RtSUvRxtEVoFjKiLeuP13Xj']),
        ];
    }
}
