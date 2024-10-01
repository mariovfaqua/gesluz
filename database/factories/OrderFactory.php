<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

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
        // Recuperar un usuario aleatorio o nulo si no existe
        $user = User::inRandomOrder()->first();

        return [
            'user_id' => $user ? $user->id : null, // Comprobar que user_id sea válido o nulo si no hay usuarios
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'), // Fecha aleatoria entre hace un año y ahora
            'precio_total' => $this->faker->randomFloat(2, 10, 900), // Precio entre 10.00 y 900.00
            'estatus' => $this->faker->boolean(), // Boolean para determinar si el pedido ha sido entregado (1) o no (0)
        ];
    }
}
