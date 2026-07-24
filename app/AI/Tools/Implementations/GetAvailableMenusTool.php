<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\MenuRepositoryInterface;

class GetAvailableMenusTool implements AITool
{
    public function __construct(
        protected MenuRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'get_available_menus';
    }

    public function description(): string
    {
        return 'Get a list of all menus that are currently available and in stock. Use this when the user asks what is available to order or wants to see the menu.';
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
        return $this->repository->getAvailableMenus();
    }
}
