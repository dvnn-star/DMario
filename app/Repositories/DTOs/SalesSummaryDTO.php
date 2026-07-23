<?php

namespace App\Repositories\DTOs;

readonly class SalesSummaryDTO
{
    public function __construct(
        public float $revenue,
        public int $orderCount,
        public float $averageOrderValue,
        public string $period
    ) {
    }
}
