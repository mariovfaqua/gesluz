<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Address;

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
        // Recuperar un usuario y dirección aleatorios o nulos si no existen
        $user = User::inRandomOrder()->first();
        $address = Address::inRandomOrder()->first();

        return [
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'), // Fecha aleatoria entre hace un año y ahora
            'precio_total' => $this->faker->randomFloat(2, 10, 900), // Precio entre 10.00 y 900.00
            'estatus' => $this->faker->randomElement([
                'pendiente_email',
                'pendiente_confirmacion',
                'pendiente_envio',
                'pendiente_recogida',
                'completado',
            ]),
            'id_user' => $user ? $user->id : null, // Comprobar que id_user sea válido o nulo si no hay usuarios
            'id_address' => $user ? $user->id : null, // Comprobar que id_user sea válido o nulo si no hay direcciones
        ];
    }
}
