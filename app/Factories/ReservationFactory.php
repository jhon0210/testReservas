<?php

namespace App\Factories;

use App\Models\Resource;
use App\Models\Reservation;

class ReservationFactory
{
    public static function create(Resource $resource, $reservedAt, $duration)
    {
        return Reservation::create([
            'resource_id' => $resource->id,
            'reserved_at' => $reservedAt,
            'duration' => $duration,
            'status' => 'confirmed',
        ]);
    }
}
