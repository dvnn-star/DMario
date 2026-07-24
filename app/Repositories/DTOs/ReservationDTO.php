<?php

namespace App\Repositories\DTOs;

readonly class ReservationDTO
{
    public function __construct(
        public string $customerName,
        public string $reservationTime,
        public int $numberOfGuests,
        public string $status,
        public ?string $tableIdentifier,
        public ?string $specialRequests
    ) {
    }
}
