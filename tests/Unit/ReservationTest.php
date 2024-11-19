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
        $resource = Resource::find(2);

        $this->assertNotNull($resource, 'El recurso con ID 1 no existe.');

        $reservedAt = '2024-11-19 10:00:00'; 
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


}
