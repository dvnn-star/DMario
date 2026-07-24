<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class GetCategoriesTool implements AITool
{
    public function __construct(
        protected CategoryRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_categories';
    }

    public function description(): string
    {
        return 'Get a list of all menu categories available in the restaurant (e.g., Food, Drinks, Desserts). Use this to see what types of items the restaurant serves.';
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
        return $this->repository->getAllCategories();
    }
}
