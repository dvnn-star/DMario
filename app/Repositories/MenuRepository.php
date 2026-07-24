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

    public function getMenuPerformance(string $sortBy = 'revenue_desc', int $limit = 10): array
    {
        // Calculate performance by joining with orderDetails
        $menus = MenuItem::with('Category')
            ->leftJoin('order_details', 'menu_items.id', '=', 'order_details.menu_item_id')
            ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
            ->where(function($query) {
                $query->where('orders.status', 'completed')
                      ->orWhereNull('orders.id');
            })
            ->selectRaw('menu_items.id, menu_items.name, menu_items.price, menu_items.category_id, 
                         COALESCE(SUM(order_details.quantity), 0) as total_quantity, 
                         COALESCE(SUM(order_details.quantity * order_details.price), 0) as total_revenue')
            ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.price', 'menu_items.category_id');

        switch ($sortBy) {
            case 'quantity_desc':
                $menus->orderByDesc('total_quantity');
                break;
            case 'quantity_asc':
                $menus->orderBy('total_quantity');
                break;
            case 'revenue_desc':
            default:
                $menus->orderByDesc('total_revenue');
                break;
        }

        $results = $menus->take($limit)->get();

        return $results->map(function ($menu) {
            return [
                'name' => $menu->name,
                'category' => $menu->Category ? $menu->Category->name : 'Unknown',
                'price' => (float) $menu->price,
                'total_quantity' => (int) $menu->total_quantity,
                'total_revenue' => (float) $menu->total_revenue,
            ];
        })->toArray();
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
