<?php

namespace App\Repositories;

use App\Models\table;
use App\Repositories\Contracts\TableRepositoryInterface;
use App\Repositories\DTOs\TableDTO;
use Illuminate\Support\Collection;

class TableRepository implements TableRepositoryInterface
{
    public function getAvailableTables(): array
    {
        $tables = table::where('status', 'available')->get();
        return $this->mapToDTOs($tables);
    }

    public function getOccupiedTables(): array
    {
        $tables = table::where('status', 'occupied')->get();
        return $this->mapToDTOs($tables);
    }

    /**
     * @param Collection $tables
     * @return TableDTO[]
     */
    protected function mapToDTOs(Collection $tables): array
    {
        return $tables->map(function ($t) {
            return new TableDTO(
                tableNumber: (string) $t->table_number,
                status: (string) $t->status,
                // Purposely hiding the internal identifier/qr code unless necessary
                identifier: null 
            );
        })->toArray();
    }
}
