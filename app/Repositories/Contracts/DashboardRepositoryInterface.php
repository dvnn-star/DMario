<?php

namespace App\Repositories\Contracts;

use App\Repositories\DTOs\DashboardSummaryDTO;

interface DashboardRepositoryInterface
{
    public function getDashboardSummary(): DashboardSummaryDTO;
}
