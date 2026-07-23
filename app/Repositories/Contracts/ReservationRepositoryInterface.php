<?php

namespace App\Repositories\Contracts;

interface ReservationRepositoryInterface
{
    /**
     * @return \App\Repositories\DTOs\ReservationDTO[]
     */
    public function getTodayReservations(): array;

    /**
     * @return \App\Repositories\DTOs\ReservationDTO[]
     */
    public function getUpcomingReservations(int $limit = 10): array;

    public function getReservationCount(): int;
}
