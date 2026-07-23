<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\OrderRepositoryInterface;

class GetRecentOrdersTool implements AITool
{
    public function __construct(
        protected OrderRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_recent_orders';
    }

    public function description(): string
    {
        return 'Get a list of the most recent orders placed in the restaurant.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'limit' => [
                    'type' => 'integer',
                    'description' => 'Maximum number of recent orders to fetch. Default is 5.',
                ]
            ],
        ];
    }

    public function execute(array $parameters): mixed
    {
        $limit = $parameters['limit'] ?? 5;
        $orders = $this->repository->getRecentOrders($limit);
        
        return [
            'recent_orders' => $orders,
        ];
    }
}
