<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\TableRepositoryInterface;

class GetTableStatusTool implements AITool
{
    public function __construct(
        protected TableRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_table_status';
    }

    public function description(): string
    {
        return 'Get the current status of all tables in the restaurant (e.g., which ones are available and which ones are occupied). Use this when the user asks about table availability.';
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
        return [
            'available' => $this->repository->getAvailableTables(),
            'occupied' => $this->repository->getOccupiedTables(),
        ];
    }
}
