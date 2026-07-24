<?php

namespace App\Repositories;

use App\Models\order;
use App\Repositories\Contracts\SalesRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SalesRepository implements SalesRepositoryInterface
{
    public function getTodaySales(): float
    {
        return (float) order::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->sum('total_price');
    }

    public function getYesterdaySales(): float
    {
        return Cache::remember('sales_yesterday', now()->addHours(24), function () {
            return (float) order::whereDate('created_at', Carbon::yesterday())
                ->where('status', 'completed')
                ->sum('total_price');
        });
    }

    public function getWeeklyRevenue(): float
    {
        return Cache::remember('sales_weekly', now()->addMinutes(30), function () {
            return (float) order::where('created_at', '>=', Carbon::now()->subDays(7))
                ->where('status', 'completed')
                ->sum('total_price');
        });
    }

    public function getMonthlyRevenue(): float
    {
        return Cache::remember('sales_monthly', now()->addMinutes(60), function () {
            return (float) order::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'completed')
                ->sum('total_price');
        });
    }

    public function getAverageOrderValue(): float
    {
        return Cache::remember('sales_aov', now()->addMinutes(30), function () {
            $data = order::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'completed')
                ->selectRaw('SUM(total_price) as total_revenue, COUNT(id) as total_orders')
                ->first();
            
            if (!$data || $data->total_orders == 0) {
                return 0.0;
            }

            return (float) ($data->total_revenue / $data->total_orders);
        });
    }

    public function getStatsByPeriod(string $period = 'today'): array
    {
        $query = order::where('status', 'completed');

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('created_at', Carbon::yesterday());
                break;
            case 'this_week':
                $query->where('created_at', '>=', Carbon::now()->startOfWeek());
                break;
            case 'this_month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                      ->whereYear('created_at', Carbon::now()->subMonth()->year);
                break;
            case 'all_time':
            default:
                break;
        }

        return [
            'total_revenue' => (float) $query->sum('total_price'),
            'total_orders' => $query->count(),
            'period' => $period,
        ];
    }

    public function getRevenueComparison(string $period1, string $period2): array
    {
        $stats1 = $this->getStatsByPeriod($period1);
        $stats2 = $this->getStatsByPeriod($period2);

        $revenue1 = $stats1['total_revenue'];
        $revenue2 = $stats2['total_revenue'];

        $difference = $revenue1 - $revenue2;
        $percentageChange = $revenue2 > 0 ? ($difference / $revenue2) * 100 : ($revenue1 > 0 ? 100 : 0);

        return [
            'period_1' => $period1,
            'period_1_revenue' => $revenue1,
            'period_2' => $period2,
            'period_2_revenue' => $revenue2,
            'difference' => $difference,
            'percentage_change' => round($percentageChange, 2),
            'trend' => $difference > 0 ? 'up' : ($difference < 0 ? 'down' : 'flat')
        ];
    }

    public function getPaymentMethodStats(string $period = 'all_time'): array
    {
        $query = order::where('status', 'completed');
        
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'this_week':
                $query->where('created_at', '>=', Carbon::now()->startOfWeek());
                break;
            case 'this_month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
        }

        $stats = $query->selectRaw('payment_method, COUNT(*) as count, SUM(total_price) as total_revenue')
            ->groupBy('payment_method')
            ->get();

        $totalOrders = $stats->sum('count');

        $result = [];
        foreach ($stats as $stat) {
            $method = $stat->payment_method ?: 'unknown';
            $result[$method] = [
                'count' => $stat->count,
                'revenue' => (float) $stat->total_revenue,
                'percentage' => $totalOrders > 0 ? round(($stat->count / $totalOrders) * 100, 2) : 0
            ];
        }

        return [
            'period' => $period,
            'total_orders' => $totalOrders,
            'distribution' => $result
        ];
    }
}
