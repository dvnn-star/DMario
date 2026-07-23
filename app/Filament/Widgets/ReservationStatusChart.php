<?php

namespace App\Filament\Widgets;

use App\Models\reservation; // Gunakan model Reservasi
use Filament\Widgets\ChartWidget;

class ReservationStatusChart extends ChartWidget
{
    protected ?string $heading = 'Status Reservasi Bulan Ini';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;
    // Auto-refresh setiap 20 detik
    protected ?string $pollingInterval = '20s';

    protected function getData(): array
    {
        // Hitung total reservasi bulan ini untuk setiap status
        $pending = reservation::where('status', 'pending')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        $confirmed = reservation::where('status', 'confirmed')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        $cancelled = reservation::where('status', 'cancelled')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total',
                    'data' => [$pending, $confirmed, $cancelled],
                    // Warna segment: Kuning (Pending), Hijau (Confirmed), Merah (Cancelled)
                    'backgroundColor' => ['#facc15', '#10b981', '#ef4444'],
                ],
            ],
            'labels' => ['Pending', 'Confirmed', 'Cancelled'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Tampilan donat (pie berlubang)
    }
}
