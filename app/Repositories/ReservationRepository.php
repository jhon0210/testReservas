<?php

namespace App\Repositories;

use App\Models\Reservation;
use Carbon\Carbon;

class ReservationRepository
{
    public function find($id)
    {
        return Reservation::find($id);
    }

    public function create(array $data)
    {
        return Reservation::create($data);
    }

    public function checkAvailability($resourceId, $reservedAt, $duration)
    {
        $reservedAtCarbon = Carbon::parse($reservedAt);

        return Reservation::where('resource_id', $resourceId)
            ->where(function ($query) use ($reservedAtCarbon, $duration) {
                $query->whereRaw(
                    '(datetime(reserved_at) <= ? AND datetime(reserved_at, "+" || duration || " minutes") > ?)',
                    [$reservedAtCarbon, $reservedAtCarbon]
                )
                ->orWhereRaw(
                    '(datetime(?, "+" || ? || " minutes") > datetime(reserved_at) AND datetime(?) <= datetime(reserved_at, "+" || duration || " minutes"))',
                    [$reservedAtCarbon, $duration, $reservedAtCarbon]
                );
            })
            ->exists();
    }

    public function deleteById($id)
    {
        $reservation = Reservation::find($id);

        if ($reservation) {
            $reservation->delete();
            return true;
        }

        return false;
    }
}



