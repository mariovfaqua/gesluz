<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word(),  // Un nombre aleatorio
            'descripcion' => $this->faker->sentence(10),  // Una oración de 10 palabras
            'precio' => $this->faker->randomFloat(2, 10, 1000),  // Un número decimal con 2 decimales entre 10 y 1000
            'material' => $this->faker->randomElement(['metal', 'plástico', 'madera', 'vidrio']),  // Un material aleatorio
            'color' => $this->faker->safeColorName(),  // Un color seguro de usar en HTML
            'marca' => $this->faker->company(),  // Un nombre de empresa como marca
            'stock' => $this->faker->numberBetween(0, 100),  // Un número entero entre 0 y 100
        ];
    }
}
