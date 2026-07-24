<?php

namespace App\AI\Context;

use App\AI\Context\Contracts\ContextBuilder;
use App\Repositories\Contracts\TableRepositoryInterface;

/**
 * Builds the table context for the AI.
 */
class TableContext implements ContextBuilder
{
    public function __construct(
        protected TableRepositoryInterface $tableRepo
    ) {
    }

    public function getName(): string
    {
        return 'tables';
    }

    public function build(): array
    {
        $available = $this->tableRepo->getAvailableTables();
        $occupied = $this->tableRepo->getOccupiedTables();

        // Extract just the table numbers to save tokens
        $availableNumbers = array_map(fn($t) => $t->tableNumber, $available);
        $occupiedNumbers = array_map(fn($t) => $t->tableNumber, $occupied);

        return [
            'available_count' => count($available),
            'available_tables' => implode(', ', $availableNumbers),
            'occupied_count' => count($occupied),
            'occupied_tables' => implode(', ', $occupiedNumbers),
            'reserved_count' => 0, // Placeholder
            'cleaning_status' => 'Pending implementation', // Placeholder
        ];
    }
}
