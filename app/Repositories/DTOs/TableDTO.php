<?php

namespace App\Repositories\DTOs;

readonly class TableDTO
{
    public function __construct(
        public string $tableNumber,
        public string $status,
        public ?string $identifier
    ) {
    }
}
