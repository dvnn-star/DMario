<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface
{
    public function getTodaySales(): float;
    public function getWeeklyRevenue(): float;
    public function getRecentOrders(int $limit = 5): array;
}
