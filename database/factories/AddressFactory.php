<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = \App\Models\Address::class;

    public function definition(): array
    {
        // Recuperar un usuario aleatorio o nulo si no existe
        $user = User::inRandomOrder()->first();

        return [
            'nombre' => fake()->name(),
            'email' => $this->faker->unique()->safeEmail, // Un correo
            'telefono' => $this->faker->phoneNumber, // Un teléfono
            'linea_1' => $this->faker->streetAddress(), // Dirección aleatoria
            'linea_2' => $this->faker->secondaryAddress(), // Segunda línea opcional
            'provincia' => $this->faker->state(),
            'ciudad' => $this->faker->city(),
            'pais' => $this->faker->country(),
            'codigo_postal' => $this->faker->postcode(),
            'primaria' => false, // Dirección primaria (false)
            'id_user' => $user ? $user->id : null, // Comprobar que id_user sea válido o nulo si no hay usuarios
        ];
    }
}
