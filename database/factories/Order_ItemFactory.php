<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'item_id' => $item ? $item->id : null, //Comprobar que item_id sea válido o nulo si no hay items
            'order_id' => $order ? $order->id : null, //Comprobar que order_id sea válido o nulo si no hay pedidos
            'cantidad' => $this->faker->numberBetween(0, 100), // Genera un número entero entre 0 y 100
        ];
    }
}
