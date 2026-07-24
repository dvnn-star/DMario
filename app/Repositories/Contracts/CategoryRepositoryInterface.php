<?php

namespace App\Repositories\Contracts;

interface CategoryRepositoryInterface
{
    /**
     * Get all menu categories.
     *
     * @return array
     */
    public function getAllCategories(): array;

    /**
     * Get menus belonging to a specific category.
     *
     * @param int $categoryId
     * @return array
     */
    public function getMenusByCategory(int $categoryId): array;
}
