<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\MenuRepositoryInterface;

class GetTopSellingMenusTool implements AITool
{
    public function __construct(
        protected MenuRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_top_selling_menus';
    }

    public function description(): string
    {
        return 'Get a list of the most popular and top-selling menu items.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'limit' => [
                    'type' => 'integer',
                    'description' => 'The number of top items to return. Default is 5.',
                ]
            ],
        ];
    }

    public function execute(array $parameters): mixed
    {
        $limit = $parameters['limit'] ?? 5;
        $items = $this->repository->getTopSelling($limit);
        
        return [
            'top_menus' => $items,
        ];
    }
}
