<?php

namespace App\Repositories\Contracts;

interface SalesRepositoryInterface
{
    public function getTodaySales(): float;
    public function getYesterdaySales(): float;
    public function getWeeklyRevenue(): float;
    public function getMonthlyRevenue(): float;
    public function getAverageOrderValue(): float;
    public function getStatsByPeriod(string $period = 'today'): array;
    public function getRevenueComparison(string $period1, string $period2): array;
    public function getPaymentMethodStats(string $period = 'all_time'): array;
}
