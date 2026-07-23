<?php

namespace App\Repositories\Contracts;

interface ReservationRepositoryInterface
{
    public function getUpcomingReservations(int $limit = 10): array;
}
