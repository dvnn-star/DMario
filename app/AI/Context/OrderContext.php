<?php

namespace App\AI\Context;

use App\AI\Context\Contracts\ContextBuilder;
use App\Repositories\Contracts\OrderRepositoryInterface;

/**
 * Builds the order context for the AI.
 */
class OrderContext implements ContextBuilder
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepo
    ) {
    }

    public function getName(): string
    {
        return 'orders';
    }

    public function build(): array
    {
        $pending = count($this->orderRepo->getPendingOrders());
        $completed = count($this->orderRepo->getCompletedOrders(50)); // cap at 50 for count context
        
        $recentOrders = $this->orderRepo->getRecentOrders(5);
        $recentSummary = [];
        foreach ($recentOrders as $order) {
            $recentSummary[] = "{$order->orderNumber} ({$order->status}): {$order->totalPrice}";
        }

        return [
            'pending_orders' => $pending,
            'completed_orders_recent' => $completed,
            'cancelled_orders' => 0, // Placeholder
            'avg_prep_time_mins' => 15, // Placeholder per instructions
            'recent_summary' => $recentSummary, // Array of strings to save tokens instead of objects
        ];
    }
}
