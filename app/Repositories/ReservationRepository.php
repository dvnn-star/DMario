<?php

namespace App\Repositories;

use App\Models\reservation;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use Illuminate\Support\Carbon;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getUpcomingReservations(int $limit = 10): array
    {
        return reservation::where('reservation_date', '>=', Carbon::today())
            ->orderBy('reservation_date')
            ->take($limit)
            ->get()
            ->toArray();
    }
}
