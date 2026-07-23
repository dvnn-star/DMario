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
