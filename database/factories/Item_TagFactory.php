<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\Tag;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item_tag>
 */
class Item_TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Recuperar un item y pedido aleatorio o nulo si no existe
        $item = Item::inRandomOrder()->first();
        $tag = Tag::inRandomOrder()->first();

        return [
            'item_id' => function () {
                return \App\Models\Item::inRandomOrder()->first()->id; // Selecciona un ID de item aleatorio
            },
            'tag_id' => function () {
                return \App\Models\Tag::inRandomOrder()->first()->id; // Selecciona un ID de tag aleatorio
            },
        ];
    }
}
