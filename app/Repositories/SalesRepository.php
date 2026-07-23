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
}
