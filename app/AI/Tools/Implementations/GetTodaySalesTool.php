<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\OrderRepositoryInterface;

class GetTodaySalesTool implements AITool
{
    public function __construct(
        protected OrderRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_today_sales';
    }

    public function description(): string
    {
        return 'Get the total sales revenue generated today.';
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
        $sales = $this->repository->getTodaySales();
        
        return [
            'today_sales' => $sales,
            'currency' => 'IDR' // Assume IDR for Dmario Batam context or adjust accordingly
        ];
    }
}
