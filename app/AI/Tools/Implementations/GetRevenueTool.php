<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\SalesRepositoryInterface;

class GetRevenueTool implements AITool
{
    public function __construct(
        protected SalesRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_revenue';
    }

    public function description(): string
    {
        return 'Get the total sales revenue generated for a specific period (e.g., today, this_week, this_month, last_month).';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'period' => [
                    'type' => 'string',
                    'description' => 'The time period to fetch revenue for. Allowed values: today, yesterday, this_week, this_month, last_month, this_year, all_time.',
                    'default' => 'this_month',
                ],
            ],
        ];
    }

    public function execute(array $parameters): mixed
    {
        $period = $parameters['period'] ?? 'this_month';
        
        $stats = $this->repository->getStatsByPeriod($period);
        $stats['currency'] = 'IDR';
        
        return $stats;
    }
}
