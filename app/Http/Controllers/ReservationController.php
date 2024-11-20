<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ReservationRepository;
use App\Repositories\ResourceRepository;
use Carbon\Carbon;


class ReservationController extends Controller
{
    protected $reservationRepo;
    protected $resourceRepo;

    public function __construct(ReservationRepository $reservationRepo, ResourceRepository $resourceRepo)
    {
        $this->reservationRepo = $reservationRepo;
        $this->resourceRepo = $resourceRepo;
    }

    public function store(Request $request)
    {
        $resourceId = $request->input('resource_id');
        $reservedAt = $request->input('reserved_at');
        $duration = $request->input('duration');

        if (!$resourceId || !$reservedAt || !$duration) {
            return response()->json(['message' => 'Faltan datos requeridos para la reserva.'], 400);
        }

        $conflicts = $this->reservationRepo->checkAvailability($resourceId, $reservedAt, $duration);

        if ($conflicts) {
            return response()->json(['message' => 'El recurso no está disponible en el horario especificado.'], 409);
        }

        $reservation = $this->reservationRepo->create([
            'resource_id' => $resourceId,
            'reserved_at' => $reservedAt,
            'duration' => $duration,
            'status' => 'confirmed',
        ]);

        return response()->json([
            'message' => 'Reserva creada con éxito.',
            'reservation' => $reservation
        ], 201);
    }


    public function destroy($id)
    {
        $deleted = $this->reservationRepo->deleteById($id);

        if ($deleted) {
            return response()->json(['message' => 'Reserva cancelada correctamente.'], 200);
        }

        return response()->json(['message' => 'No se encontró ninguna reserva con el ID especificado.'], 404);
    }

   






}
