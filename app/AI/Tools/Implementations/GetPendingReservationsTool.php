<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\ReservationRepositoryInterface;

class GetPendingReservationsTool implements AITool
{
    public function __construct(
        protected ReservationRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_pending_reservations';
    }

    public function description(): string
    {
        return 'Get a list of all pending reservations that need approval. Use this when the user asks about waiting reservations or who is trying to book a table.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => new \stdClass(),
        ];
    }

    public function execute(array $parameters): mixed
    {
        return $this->repository->getPendingReservations();
    }
}
