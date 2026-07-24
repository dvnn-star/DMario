<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\MenuItem;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories(): array
    {
        return Category::all()->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'icon' => $cat->icon,
            ];
        })->toArray();
    }

    public function getMenusByCategory(int $categoryId): array
    {
        return MenuItem::where('category_id', $categoryId)
            ->where('is_available', true)
            ->get()
            ->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'price' => $menu->price,
                    'is_recommended' => $menu->is_recommended,
                ];
            })->toArray();
    }
}
