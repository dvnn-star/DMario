<?php

namespace App\AI\Context;

use App\AI\Context\Contracts\ContextBuilder;
use App\Repositories\Contracts\SalesRepositoryInterface;
use Illuminate\Support\Facades\Cache;

/**
 * Builds the sales context for the AI.
 */
class SalesContext implements ContextBuilder
{
    public function __construct(
        protected SalesRepositoryInterface $salesRepo
    ) {
    }

    public function getName(): string
    {
        return 'sales';
    }

    public function build(): array
    {
        $cacheDuration = config('ai.context_cache.sales', 60);

        return Cache::remember('ai_context_sales', $cacheDuration, function () {
            $today = $this->salesRepo->getTodaySales();
            $yesterday = $this->salesRepo->getYesterdaySales();
            
            // Calculate growth percentage safely
            $growth = 0;
            if ($yesterday > 0) {
                $growth = round((($today - $yesterday) / $yesterday) * 100);
            }

            // Determine simple trend
            $trend = 'stable';
            if ($growth > 5) $trend = 'up';
            if ($growth < -5) $trend = 'down';

            return [
                'today_revenue' => round($today),
                'weekly_revenue' => round($this->salesRepo->getWeeklyRevenue()),
                'monthly_revenue' => round($this->salesRepo->getMonthlyRevenue()),
                'growth_pct' => $growth,
                'average_order' => round($this->salesRepo->getAverageOrderValue()),
                'refund_count' => 0, // Placeholder as refunds are not implemented
                'trend' => $trend,
            ];
        });
    }
}
