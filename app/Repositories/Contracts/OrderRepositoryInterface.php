<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface
{
    /**
     * @return \App\Repositories\DTOs\OrderDTO[]
     */
    public function getRecentOrders(int $limit = 5): array;
    
    /**
     * @return \App\Repositories\DTOs\OrderDTO[]
     */
    public function getPendingOrders(): array;

    /**
     * @return \App\Repositories\DTOs\OrderDTO[]
     */
    public function getCompletedOrders(int $limit = 10): array;

    public function getOrderCountToday(): int;
    public function getOrderCountThisMonth(): int;
    public function getTotalOrderCount(): int;
    public function updateOrderStatus(int $orderId, string $status): bool;
    
    /**
     * Get order trends aggregated by hour or day.
     * @param string $groupBy 'hour' or 'day'
     */
    public function getOrderTrends(string $groupBy = 'hour', string $period = 'today'): array;
}
