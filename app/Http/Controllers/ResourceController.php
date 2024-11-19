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

  



    


}
