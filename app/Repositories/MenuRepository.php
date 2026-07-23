<?php

namespace App\Repositories;

use App\Models\MenuItem;
use App\Repositories\Contracts\MenuRepositoryInterface;

class MenuRepository implements MenuRepositoryInterface
{
    public function getTopSelling(int $limit = 5): array
    {
        // Mock implementation since we don't know the exact schema for top selling logic
        return MenuItem::take($limit)->get()->toArray();
    }
}
