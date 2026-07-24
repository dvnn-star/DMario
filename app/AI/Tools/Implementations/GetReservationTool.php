<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\ReservationRepositoryInterface;

class GetReservationTool implements AITool
{
    public function __construct(
        protected ReservationRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_reservation';
    }

    public function description(): string
    {
        return 'Get reservation statistics for a specific period (e.g., today, this_week, this_month, last_month).';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'period' => [
                    'type' => 'string',
                    'description' => 'The time period to fetch reservations for. Allowed values: today, yesterday, this_week, this_month, last_month, this_year, all_time.',
                    'default' => 'this_month',
                ],
            ],
        ];
    }

    public function execute(array $parameters): mixed
    {
        $period = $parameters['period'] ?? 'this_month';
        
        return $this->repository->getStatsByPeriod($period);
    }
}
