<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Reservation;
use App\Repositories\ResourceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB
use Carbon\Carbon;


class ResourceController extends Controller
{
    protected $resourceRepo;

    public function __construct(ResourceRepository $resourceRepo)
    {
        $this->resourceRepo = $resourceRepo;
    }
    

    public function index()
    {
        $resources = Resource::whereDoesntHave('reservations')->get();
    
        return response()->json($resources);
    }

    public function store(Request $request)
    {
        $resource = $this->resourceRepo->create($request->all());
        return response()->json([
            'message' => 'Recurso creado con éxito.',
            'resource' => $resource
        ], 201);
    }

    public function availability($id, Request $request)
    {
        $resource = $this->resourceRepo->find($id);

        $reservedAt = $request->input('reserved_at');
        $duration = $request->input('duration');

        $conflicts = $resource->reservations()
            ->where('reserved_at', '<=', $reservedAt)
            ->whereRaw('reserved_at + INTERVAL duration MINUTE >= ?', [$reservedAt])
            ->exists();

        return response()->json(['available' => !$conflicts]);
    }

    public function validacionDisponibilidad($id, $reservedAt, $duration)
    {
        $resource = $this->resourceRepo->find($id);

        if (!$resource) {
            return response()->json(['message' => 'El recurso no existe'], 404);
        }

        if (!$reservedAt || !$duration || !is_numeric($duration)) {
            return response()->json(['message' => 'Parámetros inválidos: reserved_at y duration son requeridos y duration debe ser un número'], 422);
        }

        try {
            // Convertir reserved_at recibido a un objeto Carbon en formato militar
            $reservedAt = Carbon::parse($reservedAt)->format('Y-m-d H:i:s');
            $reservedAt = Carbon::createFromFormat('Y-m-d H:i:s', $reservedAt); 
            $duration = (int)$duration;
        } catch (\Exception $e) {
            return response()->json(['message' => 'El formato de reserved_at no es válido'], 422);
        }

        $endAt = $reservedAt->copy()->addMinutes($duration);

        // Verificar conflictos utilizando strftime para asegurar formato militar
        $conflicts = $resource->reservations()
            ->where(function ($query) use ($reservedAt, $endAt) {
                $query->whereRaw(
                    "strftime('%Y-%m-%d %H:%M:%S', reserved_at) <= ? AND strftime('%Y-%m-%d %H:%M:%S', datetime(reserved_at, '+' || duration || ' minutes')) > ?",
                    [$endAt, $reservedAt]
                );
            })
            ->exists();

        if ($conflicts) {
            return response()->json(['message' => 'El recurso no se encuentra disponible en el horario especificado']);
        }

        return response()->json(['message' => 'Recurso disponible']);
    }



}
