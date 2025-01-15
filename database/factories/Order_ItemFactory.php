<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order_Item>
 */
class Order_ItemFactory extends Factory
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
        $order = Order::inRandomOrder()->first();
        
        return [
            'id_item' => function () {
                return \App\Models\Item::inRandomOrder()->first()->id; // Selecciona un ID de item aleatorio
            },
            'id_order' => function () {
                return \App\Models\Order::inRandomOrder()->first()->id; // Selecciona un ID de order aleatorio
            },
            'cantidad' => $this->faker->numberBetween(1, 100), // Nota: Evita generar cantidades de 0
        ];
        
    }
}
