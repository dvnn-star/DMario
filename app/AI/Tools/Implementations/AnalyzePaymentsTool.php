<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\SalesRepositoryInterface;

class AnalyzePaymentsTool implements AITool
{
    public function __construct(
        protected SalesRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'analyze_payments';
    }

    public function description(): string
    {
        return 'Advanced engine to analyze payment methods used by customers (e.g. Cash, QRIS, Card). Use this to find out what percentage of payments are QRIS vs Cash.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'period' => [
                    'type' => 'string',
                    'description' => 'Time period for payment analysis (today, this_week, this_month, all_time).',
                    'default' => 'all_time'
                ]
            ],
            'required' => ['period']
        ];
    }

    public function execute(array $parameters): mixed
    {
        $period = $parameters['period'] ?? 'all_time';
        return $this->repository->getPaymentMethodStats($period);
    }
}
