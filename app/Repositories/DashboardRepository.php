<?php

namespace App\Repositories;

use App\Repositories\Contracts\DashboardRepositoryInterface;
use App\Repositories\Contracts\SalesRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\TableRepositoryInterface;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use App\Repositories\DTOs\DashboardSummaryDTO;
use App\Repositories\DTOs\SalesSummaryDTO;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function __construct(
        protected SalesRepositoryInterface $salesRepo,
        protected OrderRepositoryInterface $orderRepo,
        protected TableRepositoryInterface $tableRepo,
        protected ReservationRepositoryInterface $reservationRepo
    ) {
    }

    public function getDashboardSummary(): DashboardSummaryDTO
    {
        $todayRevenue = $this->salesRepo->getTodaySales();
        $orderCount = $this->orderRepo->getOrderCountToday();
        $aov = $orderCount > 0 ? $todayRevenue / $orderCount : 0.0;

        $salesSummary = new SalesSummaryDTO(
            revenue: $todayRevenue,
            orderCount: $orderCount,
            averageOrderValue: $aov,
            period: 'today'
        );

        $pendingOrdersCount = count($this->orderRepo->getPendingOrders());
        $availableTablesCount = count($this->tableRepo->getAvailableTables());
        $todayReservationsCount = $this->reservationRepo->getReservationCount();

        return new DashboardSummaryDTO(
            todaySales: $salesSummary,
            pendingOrders: $pendingOrdersCount,
            availableTables: $availableTablesCount,
            todayReservations: $todayReservationsCount
        );
    }
}
