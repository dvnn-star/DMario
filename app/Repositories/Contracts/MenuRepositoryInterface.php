<?php

namespace App\Repositories\Contracts;

interface MenuRepositoryInterface
{
    public function getTopSelling(int $limit = 5): array;
}
