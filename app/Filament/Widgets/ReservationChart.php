<?php

namespace App\Filament\Widgets;

use App\Models\order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ReservationChart extends ChartWidget
{
    protected  ?string $heading = 'Grafik Pendapatan';

    // Auto-refresh setiap 15 detik
    protected ?string $pollingInterval = '15s';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1; // Atau 2 jika grid panel 3 kolom

    // Filter default saat halaman pertama kali dimuat
    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Tahun Ini (Per Bulan)',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter ?? 'month';

        $data = [];
        $labels = [];

        match ($activeFilter) {
            'today' => $this->getTodayData($data, $labels),
            'week'  => $this->getWeekData($data, $labels),
            'month' => $this->getMonthData($data, $labels),
        };

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    private function getTodayData(array &$data, array &$labels): void
    {
        // Loop jam 00:00 s.d 23:00 untuk hari ini
        for ($hour = 0; $hour < 24; $hour++) {
            $labels[] = sprintf('%02d:00', $hour);

            $data[] = order::where('status', 'completed')
                ->whereDate('created_at', Carbon::today())
                ->whereRaw('HOUR(created_at) = ?', [$hour]) // PERBAIKAN DI SINI
                ->sum('total_price');
        }
    }

    private function getWeekData(array &$data, array &$labels): void
    {
        // Loop 7 hari pada minggu ini (Senin s.d Minggu)
        $startOfWeek = Carbon::now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $date = (clone $startOfWeek)->addDays($i);

            $labels[] = $date->translatedFormat('D, d M');

            $data[] = order::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_price');
        }
    }

    private function getMonthData(array &$data, array &$labels): void
    {
        // Loop 12 bulan pada tahun berjalan
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        for ($month = 1; $month <= 12; $month++) {
            $data[] = order::where('status', 'completed')
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $month)
                ->sum('total_price');
        }
    }

    protected function getType(): string
    {
        return 'line';
    }
}
