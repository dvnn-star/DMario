<?php

namespace App\Repositories\DTOs;

readonly class DashboardSummaryDTO
{
    /**
     * @param SalesSummaryDTO $todaySales
     * @param int $pendingOrders
     * @param int $availableTables
     * @param int $todayReservations
     */
    public function __construct(
        public SalesSummaryDTO $todaySales,
        public int $pendingOrders,
        public int $availableTables,
        public int $todayReservations
    ) {
    }
}
