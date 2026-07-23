<?php

namespace App\Repositories;

use App\Models\order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Carbon;

class OrderRepository implements OrderRepositoryInterface
{
    public function getTodaySales(): float
    {
        // Example implementation
        return (float) order::whereDate('created_at', Carbon::today())->sum('total_amount');
    }

    public function getWeeklyRevenue(): float
    {
        return (float) order::where('created_at', '>=', Carbon::now()->subDays(7))->sum('total_amount');
    }

    public function getRecentOrders(int $limit = 5): array
    {
        return order::latest()->take($limit)->get()->toArray();
    }
}
