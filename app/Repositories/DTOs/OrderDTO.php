<?php

namespace App\Repositories\DTOs;

readonly class OrderDTO
{
    public function __construct(
        public string $orderNumber,
        public string $status,
        public float $totalPrice,
        public ?string $paymentMethod,
        public ?string $tableIdentifier,
        public string $createdAt
    ) {
    }
}
