<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\OrderRepositoryInterface;

class GetOrderStatisticsTool implements AITool
{
    public function __construct(
        protected OrderRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_order_statistics';
    }

    public function description(): string
    {
        return 'Get the total number of orders placed (today, this month, and overall).';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => new \stdClass(),
        ];
    }

    public function execute(array $parameters): mixed
    {
        return [
            'orders_today' => $this->repository->getOrderCountToday(),
            'orders_this_month' => $this->repository->getOrderCountThisMonth(),
            'total_orders' => $this->repository->getTotalOrderCount(),
        ];
    }
}
