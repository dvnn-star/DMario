<?php

namespace App\Repositories\Contracts;

interface TableRepositoryInterface
{
    /**
     * @return \App\Repositories\DTOs\TableDTO[]
     */
    public function getAvailableTables(): array;

    /**
     * @return \App\Repositories\DTOs\TableDTO[]
     */
    public function getOccupiedTables(): array;
}
