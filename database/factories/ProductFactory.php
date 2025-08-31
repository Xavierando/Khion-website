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
        $quantity = fake()->numberBetween(30, 50);

        return [
            'code' => array_reduce(fake()->words(2), fn ($c, $i) => $c.' '.$i),
            'name' => array_reduce(fake()->words(2), fn ($c, $i) => $c.' '.$i),
            'description' => 'La nostra tavola da snowboard è pensata per offrire prestazioni eccezionali su ogni tipo di terreno, combinando innovazione tecnologica e design funzionale. Realizzata con un nucleo in legno di pioppo e rinforzata con fibra di vetro, questa tavola garantisce un perfetto equilibrio tra leggerezza, resistenza e reattività, assicurando una guida fluida e precisa in ogni condizione.
La forma direzionale twin la rende versatile e adatta a tutti gli stili di guida, dal freeride al freestyle, passando per l’all-mountain. Grazie alla tecnologia rocker/camber ibrida, la tavola offre una manovrabilità eccezionale e un ottimo pop, ideale per salti e trick, mentre la base sinterizzata assicura una scivolata rapida e duratura, anche su neve compatta o ghiacciata.
I bordi in acciaio proteggono la tavola dagli urti e dall’usura, prolungandone la vita utile, mentre la grafica accattivante la rende unica e riconoscibile. Disponibile in diverse misure, si adatta a ogni rider, dal principiante all’esperto, offrendo sempre il massimo in termini di controllo, stabilità e divertimento.
Perfetta per chi cerca una tavola affidabile, performante e pronta a sfidare ogni pista, questa snowboard è la scelta ideale per vivere appieno l’emozione della montagna.',
            'base_price' => fake()->numberBetween(100, 900),
            'quantity' => $quantity,
            'available_quantity' => $quantity,
            'configuration' => $json,
            'stripe_id' => 'prod_SwLWdtjtwhnHfP',
            'stripe_price_id' => 'price_1S0SpXLH7yhts3sR3fiUydCd',
        ];
    }
}
