<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\ReservationRepositoryInterface;

class GetReservationSummaryTool implements AITool
{
    public function __construct(
        protected ReservationRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_reservation_summary';
    }

    public function description(): string
    {
        return 'Get a summary of upcoming reservations.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'limit' => [
                    'type' => 'integer',
                    'description' => 'Maximum number of reservations to fetch. Default is 10.',
                ]
            ],
        ];
    }

    public function execute(array $parameters): mixed
    {
        $limit = $parameters['limit'] ?? 10;
        $reservations = $this->repository->getUpcomingReservations($limit);
        
        return [
            'upcoming_reservations' => $reservations,
        ];
    }
}
