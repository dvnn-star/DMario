<?php

namespace App\Repositories\Contracts;

interface SalesRepositoryInterface
{
    public function getTodaySales(): float;
    public function getYesterdaySales(): float;
    public function getWeeklyRevenue(): float;
    public function getMonthlyRevenue(): float;
    public function getAverageOrderValue(): float;
}
