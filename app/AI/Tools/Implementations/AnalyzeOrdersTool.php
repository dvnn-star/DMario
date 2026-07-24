<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\OrderRepositoryInterface;

class AnalyzeOrdersTool implements AITool
{
    public function __construct(
        protected OrderRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'analyze_orders';
    }

    public function description(): string
    {
        return 'Advanced engine to analyze order trends. Can fetch basic stats, recent orders, or time-series data to find the busiest hour or highest selling day.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'query_type' => [
                    'type' => 'string',
                    'description' => 'The type of order query: "stats", "recent", or "trends".',
                    'enum' => ['stats', 'recent', 'trends'],
                    'default' => 'stats'
                ],
                'trend_group_by' => [
                    'type' => 'string',
                    'description' => 'If query_type is trends, how to group the data: "hour" (for busiest hours) or "day" (for busiest days).',
                    'enum' => ['hour', 'day'],
                ],
                'period' => [
                    'type' => 'string',
                    'description' => 'Time period for the query (today, this_week, this_month).',
                    'default' => 'today'
                ]
            ],
            'required' => ['query_type']
        ];
    }

    public function execute(array $parameters): mixed
    {
        $type = $parameters['query_type'] ?? 'stats';
        
        if ($type === 'recent') {
            return $this->repository->getRecentOrders(10);
        }

        if ($type === 'trends') {
            $groupBy = $parameters['trend_group_by'] ?? 'hour';
            $period = $parameters['period'] ?? 'today';
            return $this->repository->getOrderTrends($groupBy, $period);
        }

        return [
            'orders_today' => $this->repository->getOrderCountToday(),
            'orders_this_month' => $this->repository->getOrderCountThisMonth(),
            'total_orders' => $this->repository->getTotalOrderCount(),
        ];
    }
}
