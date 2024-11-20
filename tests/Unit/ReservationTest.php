<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\ReservationRepository; 
use App\Repositories\ResourceRepository;
use App\Models\Resource;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    protected $reservationRepo;
    protected $resourceRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservationRepo = $this->createMock(ReservationRepository::class);
    }

    public function testRecursoDisponible_reservaViable()
    {
        $resource = Resource::factory()->create();

        $this->assertNotNull($resource, 'No se pudo crear el recurso.');

        $reservedAt = '2026-11-19T10:00:00';
        $duration = 60;

        $reservation = Reservation::create([
            'resource_id' => $resource->id,
            'reserved_at' => $reservedAt,
            'duration' => $duration,
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('reservations', [
            'resource_id' => $resource->id,
            'reserved_at' => $reservedAt,
            'duration' => $duration,
        ]);
    }  

    public function testRecursoNoDisponible_reservaConflicto()
    {
        $resource = Resource::factory()->create();

        $this->assertNotNull($resource, 'No se pudo crear el recurso.');

        $existingReservedAt = '2026-11-19T10:00:00';
        $existingDuration = 60;

        $existingReservation = Reservation::create([
            'resource_id' => $resource->id,
            'reserved_at' => $existingReservedAt,
            'duration' => $existingDuration,
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('reservations', [
            'resource_id' => $resource->id,
            'reserved_at' => $existingReservedAt,
            'duration' => $existingDuration,
        ]);

        $newReservedAt = '2026-11-19T10:30:00'; 
        $newDuration = 60;

        $this->reservationRepo
        ->method('checkAvailability')
        ->with($resource->id, $newReservedAt, $newDuration)
        ->willReturn(true); // Indica que hay conflicto

        $response = $this->postJson(route('reservations.store'), [
            'resource_id' => $resource->id,
            'reserved_at' => $newReservedAt,
            'duration' => $newDuration,
        ]);

        $response->assertStatus(409); 
        $response->assertJson([
            'message' => 'El recurso no está disponible en el horario especificado.',
        ]);

        $this->assertDatabaseMissing('reservations', [
            'resource_id' => $resource->id,
            'reserved_at' => $newReservedAt,
            'duration' => $newDuration,
        ]);
    }

    public function testCancelarReservaPorResourceId()
    {
        $resource = Resource::factory()->create();
        $this->assertNotNull($resource, 'No se pudo crear el recurso.');

        $reservation = Reservation::create([
            'resource_id' => $resource->id,
            'reserved_at' => '2026-11-19T10:00:00',
            'duration' => 60,
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('reservations', [
            'resource_id' => $resource->id,
            'status' => 'confirmed',
        ]);

        if ($reservation->status === 'cancelled') {
            $this->assertEquals('cancelled', $reservation->status, 'El estado de la reserva debería ser "cancelled".');
            $this->assertTrue(true, 'La reserva ya está cancelada.');
            return;
        }

        $reservation->update(['status' => 'cancelled']);

        $this->assertEquals('cancelled', $reservation->status, 'El estado de la reserva debería ser "cancelled".');

        $this->assertDatabaseHas('reservations', [
            'resource_id' => $resource->id,
            'id' => $reservation->id,
            'status' => 'cancelled',
        ]);

        $this->assertTrue(true, 'La reserva fue cancelada exitosamente.');
    }

}
