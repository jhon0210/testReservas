<?php

namespace Tests\Feature; 

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Resource;
use App\Models\Reservation;

class IntegracionTest extends TestCase
{
    use RefreshDatabase;

    public function testRecursoDisponibleReservaViable()
    {
        $resource = Resource::factory()->create();

        $reservedAt = '2026-11-19 10:00:00'; 
        $duration = 60; 

        Reservation::factory()->create([
            'resource_id' => $resource->id,
            'reserved_at' => $reservedAt,
            'duration' => $duration,
        ]);

        $this->assertDatabaseHas('reservations', [
            'resource_id' => $resource->id,
            'reserved_at' => $reservedAt,
            'duration' => $duration,
        ]);
    }
}
