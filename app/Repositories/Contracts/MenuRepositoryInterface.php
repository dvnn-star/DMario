<?php

namespace App\Repositories\Contracts;

interface MenuRepositoryInterface
{
    /**
     * @return \App\Repositories\DTOs\MenuDTO[]
     */
    public function getTopSellingMenus(int $limit = 5): array;

    /**
     * @return \App\Repositories\DTOs\MenuDTO[]
     */
    public function getLeastSellingMenus(int $limit = 5): array;

    /**
     * @return \App\Repositories\DTOs\MenuDTO[]
     */
    public function getAvailableMenus(): array;

    /**
     * @return \App\Repositories\DTOs\MenuDTO[]
     */
    public function getUnavailableMenus(): array;
}
