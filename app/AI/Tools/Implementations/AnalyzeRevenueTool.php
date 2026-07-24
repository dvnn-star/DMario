<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\SalesRepositoryInterface;

class AnalyzeRevenueTool implements AITool
{
    public function __construct(
        protected SalesRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'analyze_revenue';
    }

    public function description(): string
    {
        return 'Advanced engine to analyze revenue, average order value, and compare revenue between periods (e.g., this month vs last month). Use this for any revenue-related questions.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'metric' => [
                    'type' => 'string',
                    'description' => 'The metric to fetch: "total", "aov" (Average Order Value), or "comparison".',
                    'enum' => ['total', 'aov', 'comparison'],
                    'default' => 'total'
                ],
                'period' => [
                    'type' => 'string',
                    'description' => 'Primary time period (today, yesterday, this_week, this_month, last_month, all_time).',
                    'default' => 'this_month'
                ],
                'compare_with' => [
                    'type' => 'string',
                    'description' => 'Secondary time period to compare against (required if metric is comparison).',
                ]
            ],
            'required' => ['metric', 'period']
        ];
    }

    public function execute(array $parameters): mixed
    {
        $metric = $parameters['metric'] ?? 'total';
        $period = $parameters['period'] ?? 'this_month';

        if ($metric === 'comparison') {
            $compareWith = $parameters['compare_with'] ?? 'last_month';
            return $this->repository->getRevenueComparison($period, $compareWith);
        }

        if ($metric === 'aov') {
            return [
                'metric' => 'Average Order Value',
                'value' => $this->repository->getAverageOrderValue()
            ];
        }

        return $this->repository->getStatsByPeriod($period);
    }
}
