<?php
namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word, // Nombre del recurso
            'descripcion' => $this->faker->sentence, // Descripción del recurso
            'capacidad' => $this->faker->numberBetween(1, 100), // Capacidad del recurso
        ];
    }
}
