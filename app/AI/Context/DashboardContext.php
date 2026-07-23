<?php

namespace App\AI\Context;

use App\AI\Context\Contracts\ContextBuilder;
use App\Repositories\Contracts\DashboardRepositoryInterface;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Support\Facades\Cache;

/**
 * Builds the dashboard summary context for the AI.
 */
class DashboardContext implements ContextBuilder
{
    public function __construct(
        protected DashboardRepositoryInterface $dashboardRepo,
        protected MenuRepositoryInterface $menuRepo
    ) {
    }

    public function getName(): string
    {
        return 'dashboard';
    }

    public function build(): array
    {
        $cacheDuration = config('ai.context_cache.dashboard', 30);

        return Cache::remember('ai_context_dashboard', $cacheDuration, function () {
            $summary = $this->dashboardRepo->getDashboardSummary();
            $topMenus = $this->menuRepo->getTopSellingMenus(1);
            
            $topMenuName = !empty($topMenus) ? $topMenus[0]->name : 'None';

            // Calculate growth if we had yesterday's sales (mocking 0 growth if unavailable)
            // Assuming we only have today's sales for now
            $salesGrowth = 0; // Replace with actual growth logic when yesterday's sales are integrated into dashboard DTO

            return [
                'today_sales' => round($summary->todaySales->revenue),
                'sales_growth' => $salesGrowth,
                'orders' => $summary->todaySales->orderCount,
                'average_order' => round($summary->todaySales->averageOrderValue),
                'reservation_count' => $summary->todayReservations,
                'available_tables' => $summary->availableTables,
                // Assuming we don't have occupied tables count in DTO directly, 
                // but we might need it. For now, leave null or omit if unknown.
                'top_menu' => $topMenuName,
            ];
        });
    }
}
