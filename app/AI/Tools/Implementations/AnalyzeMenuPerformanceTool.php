<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\MenuRepositoryInterface;

class AnalyzeMenuPerformanceTool implements AITool
{
    public function __construct(
        protected MenuRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'analyze_menu_performance';
    }

    public function description(): string
    {
        return 'Advanced engine to analyze which menus are performing well and which are dead stock. Use this to find top selling menus, least selling menus, or highest revenue generating menus.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'sort_by' => [
                    'type' => 'string',
                    'description' => 'How to sort the performance data: "revenue_desc" (highest revenue), "quantity_desc" (most ordered), or "quantity_asc" (least ordered / dead stock).',
                    'enum' => ['revenue_desc', 'quantity_desc', 'quantity_asc'],
                    'default' => 'revenue_desc'
                ],
                'limit' => [
                    'type' => 'integer',
                    'description' => 'Number of menus to return. Default is 10.',
                    'default' => 10
                ]
            ],
            'required' => ['sort_by']
        ];
    }

    public function execute(array $parameters): mixed
    {
        $sortBy = $parameters['sort_by'] ?? 'revenue_desc';
        $limit = $parameters['limit'] ?? 10;
        
        return $this->repository->getMenuPerformance($sortBy, $limit);
    }
}
