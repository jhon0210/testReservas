<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        return [
            'resource_id' => $this->faker->numberBetween(1, 10), // Ajusta según tus necesidades
            'reserved_at' => $this->faker->dateTimeThisYear(), // Fecha aleatoria dentro de este año
            'duration' => $this->faker->numberBetween(30, 120), // Duración aleatoria entre 30 y 120 minutos
            'status' => 'confirmed', // Estado por defecto
        ];
    }
}
