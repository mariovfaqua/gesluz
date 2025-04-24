<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;

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
        // Recuperar una marca aleatoria o nulo si no existe
        $brand = Brand::inRandomOrder()->first();

        return [
            'nombre' => $this->faker->word(),  // Un nombre aleatorio
            'descripcion' => $this->faker->sentence(10),  // Una oración de 10 palabras
            'precio' => $this->faker->randomFloat(2, 10, 1000),  // Un número decimal con 2 decimales entre 10 y 1000
            'distribucion' => $this->faker->randomElement(['Salón', 'Dormitorio', 'Cocina', 'Baño', 'Jardín', 'Otro']), // Distribución aleatoria
            'tipo' => $this->faker->randomElement(['Plafón', 'Sobremesa', 'Auxiliar', 'Colgante', 'Empotrada', 'De pie', 'Foco', 'Tira led', 'Repuesto']),  // Un tipo aleatorio
            'alto' => $this->faker->randomFloat(2, 10, 1000),  // Un número decimal con 2 decimales entre 10 y 1000
            'ancho' => $this->faker->randomFloat(2, 10, 1000),  // Un número decimal con 2 decimales entre 10 y 1000
            'stock' => $this->faker->numberBetween(0, 100),  // Un número entero entre 0 y 100
            'id_brand' => $brand ? $brand->id : null, // Comprobar que id_brand sea válido o nulo si no hay marcas
        ];
    }
}
