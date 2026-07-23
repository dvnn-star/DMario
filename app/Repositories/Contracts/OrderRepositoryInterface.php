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
}
