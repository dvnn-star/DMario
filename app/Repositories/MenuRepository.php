<?php

namespace App\Repositories;

use App\Models\MenuItem;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\DTOs\MenuDTO;
use Illuminate\Support\Collection;

class MenuRepository implements MenuRepositoryInterface
{
    public function getTopSellingMenus(int $limit = 5): array
    {
        // Mocking top-selling logic. Usually this involves a join with orderDetails and grouping.
        // For simplicity, we just order by is_recommended for now.
        $menus = MenuItem::with('Category')
            ->where('is_available', true)
            ->orderByDesc('is_recommended')
            ->take($limit)
            ->get();

        return $this->mapToDTOs($menus);
    }

    public function getLeastSellingMenus(int $limit = 5): array
    {
        // Mocking least selling
        $menus = MenuItem::with('Category')
            ->where('is_available', true)
            ->orderBy('is_recommended')
            ->take($limit)
            ->get();

        return $this->mapToDTOs($menus);
    }

    public function getAvailableMenus(): array
    {
        $menus = MenuItem::with('Category')
            ->where('is_available', true)
            ->get();

        return $this->mapToDTOs($menus);
    }

    public function getUnavailableMenus(): array
    {
        $menus = MenuItem::with('Category')
            ->where('is_available', false)
            ->get();

        return $this->mapToDTOs($menus);
    }

    /**
     * @param Collection $menus
     * @return MenuDTO[]
     */
    protected function mapToDTOs(Collection $menus): array
    {
        return $menus->map(function ($menu) {
            return new MenuDTO(
                name: (string) $menu->name,
                price: (float) $menu->price,
                type: (string) $menu->type,
                isAvailable: (bool) $menu->is_available,
                isRecommended: (bool) $menu->is_recommended,
                categoryName: $menu->Category ? (string) $menu->Category->name : null
            );
        })->toArray();
    }
}
