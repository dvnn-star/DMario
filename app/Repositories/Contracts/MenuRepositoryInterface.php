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
    
    /**
     * Get menu revenue contribution sorted by revenue or quantity.
     * @param string $sortBy 'revenue_desc', 'quantity_desc', 'quantity_asc'
     */
    public function getMenuPerformance(string $sortBy = 'revenue_desc', int $limit = 10): array;
}
