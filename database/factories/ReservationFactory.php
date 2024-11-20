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
            'resource_id' => $this->faker->numberBetween(1, 10),
            'reserved_at' => $this->faker->dateTimeThisYear(),
            'duration' => $this->faker->numberBetween(30, 120), 
            'status' => 'confirmed', 
        ];
    }

}
