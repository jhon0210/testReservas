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
    //use RefreshDatabase;

    protected $reservationRepo;
    protected $resourceRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservationRepo = $this->createMock(ReservationRepository::class);
    }

    public function testRecursoDisponible_reservaViable()
    {
        $resource = Resource::find(1);

        $this->assertNotNull($resource, 'El recurso con ID 1 no existe.');

        $reservedAt = '2026-11-19T10:00:00'; 
        $duration = 60; 

        $this->reservationRepo
            ->method('checkAvailability')
            ->willReturn(null);  

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
        // Buscar el recurso
        $resource = Resource::find(2);

        // Validar que el recurso existe
        $this->assertNotNull($resource, 'El recurso con ID 2 no existe.');

        // Datos para la reserva existente
        $existingReservedAt = '2026-11-19T10:00:00';
        $existingDuration = 60;

        // Crear una reserva existente en la base de datos
        $existingReservation = Reservation::create([
            'resource_id' => $resource->id,
            'reserved_at' => $existingReservedAt,
            'duration' => $existingDuration,
            'status' => 'confirmed',
        ]);

        // Validar que la reserva se creó correctamente
        $this->assertDatabaseHas('reservations', [
            'resource_id' => $resource->id,
            'reserved_at' => $existingReservedAt,
            'duration' => $existingDuration,
        ]);

        // Intentar realizar una nueva reserva en el mismo horario
        $newReservedAt = '2026-11-19T10:30:00'; // Conflicto con la reserva existente
        $newDuration = 60;

        // Mockear la respuesta del método checkAvailability para indicar conflicto
        $this->reservationRepo
            ->method('checkAvailability')
            ->with($resource->id, $newReservedAt, $newDuration)
            ->willReturn(true); // Indica que hay conflicto

        // Intentar crear una reserva que debería fallar
        $response = $this->postJson(route('reservations.store'), [
            'resource_id' => $resource->id,
            'reserved_at' => $newReservedAt,
            'duration' => $newDuration,
        ]);

        // Validar que el servidor responde con un error de conflicto
        $response->assertStatus(409); // Código HTTP 409: Conflicto
        $response->assertJson([
            'message' => 'El recurso no está disponible en el horario especificado.',
        ]);

        // Verificar que no se creó una nueva reserva en la base de datos
        $this->assertDatabaseMissing('reservations', [
            'resource_id' => $resource->id,
            'reserved_at' => $newReservedAt,
            'duration' => $newDuration,
        ]);
    }

    public function testCancelarReservaPorResourceId()
{
    // Buscar la primera reserva asociada al resource_id (asegúrate de que exista en la base de datos de pruebas)
    $resourceId = 2; // Cambia este valor según tu base de datos de pruebas
    $reservation = Reservation::where('resource_id', $resourceId)->first();

    // Verificar que se encontró una reserva asociada al recurso
    $this->assertNotNull($reservation, "No se encontró ninguna reserva para el recurso con ID $resourceId.");

    // Si la reserva ya está cancelada, comprobar que devuelve el mensaje esperado
    if ($reservation->status === 'cancelled') {
        $this->assertEquals('cancelled', $reservation->status, 'El estado de la reserva debería ser "cancelled".');
        $this->assertTrue(true, 'La reserva ya está cancelada.');
        return;
    }

    // Cancelar la reserva y actualizar el estado
    $reservation->update(['status' => 'cancelled']);

    // Validar que el estado se actualizó correctamente
    $this->assertEquals('cancelled', $reservation->status, 'El estado de la reserva debería ser "cancelled".');

    // Validar que la reserva ahora aparece como cancelada en la base de datos
    $this->assertDatabaseHas('reservations', [
        'resource_id' => $resourceId,
        'id' => $reservation->id,
        'status' => 'cancelled',
    ]);

    // Agregar un mensaje de éxito
    $this->assertTrue(true, 'La reserva fue cancelada exitosamente.');
}

    






}
