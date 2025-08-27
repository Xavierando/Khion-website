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
            'code' => array_reduce(fake()->words(2), fn ($c, $i) => $c.' '.$i),
            'name' => array_reduce(fake()->words(2), fn ($c, $i) => $c.' '.$i),
            'description' => fake()->text(600),
            'base_price' => fake()->numberBetween(100, 900),
            'quantity' => fake()->numberBetween(1, 5),
            'configuration' => $json,
            'stripe_id' => 'prod_SwLWdtjtwhnHfP',
            'stripe_price_id' => 'price_1S0SpXLH7yhts3sR3fiUydCd',
        ];
    }
}
