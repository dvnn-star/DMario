<?php

namespace App\AI\Context;

use App\AI\Context\Contracts\ContextBuilder;
use App\Repositories\Contracts\MenuRepositoryInterface;

/**
 * Builds the menu context for the AI.
 */
class MenuContext implements ContextBuilder
{
    public function __construct(
        protected MenuRepositoryInterface $menuRepo
    ) {
    }

    public function getName(): string
    {
        return 'menus';
    }

    public function build(): array
    {
        $topSelling = $this->menuRepo->getTopSellingMenus(3);
        $leastSelling = $this->menuRepo->getLeastSellingMenus(3);
        $unavailable = $this->menuRepo->getUnavailableMenus();

        // Calculate average price from available items
        $available = $this->menuRepo->getAvailableMenus();
        $totalPrice = 0;
        foreach ($available as $item) {
            $totalPrice += $item->price;
        }
        $avgPrice = count($available) > 0 ? round($totalPrice / count($available)) : 0;

        return [
            'top_selling' => array_map(fn($m) => $m->name, $topSelling),
            'lowest_selling' => array_map(fn($m) => $m->name, $leastSelling),
            'unavailable' => array_map(fn($m) => $m->name, $unavailable),
            'avg_price' => $avgPrice,
            'categories' => ['Main Course', 'Beverage', 'Dessert'], // Placeholder for category summary
        ];
    }
}
