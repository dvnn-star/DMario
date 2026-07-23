<?php

namespace App\Repositories\DTOs;

readonly class MenuDTO
{
    public function __construct(
        public string $name,
        public float $price,
        public string $type,
        public bool $isAvailable,
        public bool $isRecommended,
        public ?string $categoryName
    ) {
    }
}
