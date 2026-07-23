<?php

namespace App\Filament\Widgets;

use App\Models\order;
use App\Models\reservation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected  ?string $pollingInterval = '10s';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Pesanan',
                order::count()
            )
                ->description('Total seluruh transaksi')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make(
                'Reservasi Pending',
                reservation::where('status', 'pending')->count()
            )
                ->description('Menunggu konfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make(
                'Total Pendapatan',
                'Rp ' . number_format(order::where('status', 'completed')->sum('total_price'), 0, ',', '.')
            )
                ->description('Dari pesanan selesai/lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
