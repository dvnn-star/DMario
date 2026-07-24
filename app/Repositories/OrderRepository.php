<?php

namespace App\Repositories;

use App\Models\order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\DTOs\OrderDTO;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function getRecentOrders(int $limit = 5): array
    {
        $orders = order::with('table')
            ->latest()
            ->take($limit)
            ->get();

        return $this->mapToDTOs($orders);
    }

    public function getPendingOrders(): array
    {
        $orders = order::with('table')
            ->whereIn('status', ['pending', 'processing'])
            ->latest()
            ->get();

        return $this->mapToDTOs($orders);
    }

    public function getCompletedOrders(int $limit = 10): array
    {
        $orders = order::with('table')
            ->where('status', 'completed')
            ->latest()
            ->take($limit)
            ->get();

        return $this->mapToDTOs($orders);
    }

    public function getOrderCountToday(): int
    {
        return order::whereDate('created_at', Carbon::today())->count();
    }

    public function getOrderCountThisMonth(): int
    {
        return order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
    }

    public function getTotalOrderCount(): int
    {
        return order::count();
    }

    public function updateOrderStatus(int $orderId, string $status): bool
    {
        $order = order::find($orderId);
        if (!$order) {
            return false;
        }

        $order->status = $status;
        return $order->save();
    }

    public function getOrderTrends(string $groupBy = 'hour', string $period = 'today'): array
    {
        $query = order::query();

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

        $orders = $query->get();
        $trends = [];

        foreach ($orders as $order) {
            $key = $groupBy === 'hour' 
                ? Carbon::parse($order->created_at)->format('H:00')
                : Carbon::parse($order->created_at)->format('Y-m-d (l)');

            if (!isset($trends[$key])) {
                $trends[$key] = [
                    'label' => $key,
                    'count' => 0,
                    'revenue' => 0
                ];
            }
            $trends[$key]['count']++;
            $trends[$key]['revenue'] += $order->total_price;
        }

        // Sort by label
        ksort($trends);
        
        return [
            'period' => $period,
            'group_by' => $groupBy,
            'trends' => array_values($trends)
        ];
    }

    /**
     * @param Collection $orders
     * @return OrderDTO[]
     */
    protected function mapToDTOs(Collection $orders): array
    {
        return $orders->map(function ($order) {
            return new OrderDTO(
                orderNumber: (string) $order->id, // Use ID or actual order number if it exists
                status: (string) $order->status,
                totalPrice: (float) $order->total_price,
                paymentMethod: $order->payment_method ? (string) $order->payment_method : null,
                tableIdentifier: $order->table ? (string) $order->table->table_number : null,
                createdAt: $order->created_at->toDateTimeString()
            );
        })->toArray();
    }
}
