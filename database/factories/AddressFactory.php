<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
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
            'linea_1' => $this->faker->streetAddress(), // Formato de dirección
            'linea_2' => $this->faker->optional()->secondaryAddress(), // Opcional, segunda línea de dirección
            'provincia' => $this->faker->state(), // Utilización de state en lugar de provincia
            'ciudad' => $this->faker->city(), // Nombre de ciudad
            'pais' => $this->faker->country(), // Nombre de país
            'codigo_postal' => $this->faker->postcode(), // Un código postal
            'primaria' => $this->faker->boolean(), // Boolean para determinar si es ladirección primaria
        ];
    }
}
