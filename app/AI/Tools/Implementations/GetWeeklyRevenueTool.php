<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\OrderRepositoryInterface;

class GetWeeklyRevenueTool implements AITool
{
    public function __construct(
        protected OrderRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_weekly_revenue';
    }

    public function description(): string
    {
        return 'Get the total sales revenue generated over the last 7 days.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [],
        ];
    }

    public function execute(array $parameters): mixed
    {
        $revenue = $this->repository->getWeeklyRevenue();
        
        return [
            'weekly_revenue' => $revenue,
            'currency' => 'IDR'
        ];
    }
}
