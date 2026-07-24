<?php

namespace App\Repositories;

use App\Models\reservation;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use App\Repositories\DTOs\ReservationDTO;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getTodayReservations(): array
    {
        $reservations = reservation::with('table')
            ->whereDate('reservation_time', Carbon::today())
            ->orderBy('reservation_time')
            ->get();
            
        return $this->mapToDTOs($reservations);
    }

    public function getUpcomingReservations(int $limit = 10): array
    {
        $reservations = reservation::with('table')
            ->where('reservation_time', '>=', Carbon::now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('reservation_time')
            ->take($limit)
            ->get();

        return $this->mapToDTOs($reservations);
    }

    public function getReservationCount(): int
    {
        return reservation::whereDate('reservation_time', Carbon::today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
    }

    /**
     * @param Collection $reservations
     * @return ReservationDTO[]
     */
    protected function mapToDTOs(Collection $reservations): array
    {
        return $reservations->map(function ($reservation) {
            return new ReservationDTO(
                customerName: (string) $reservation->customer_name,
                reservationTime: $reservation->reservation_time,
                numberOfGuests: (int) $reservation->number_of_guests,
                status: (string) $reservation->status,
                tableIdentifier: $reservation->table ? (string) $reservation->table->table_number : null,
                specialRequests: $reservation->special_requests ? (string) $reservation->special_requests : null
            );
        })->toArray();
    }

    public function getStatsByPeriod(string $period = 'today'): array
    {
        $query = reservation::whereIn('status', ['pending', 'confirmed']);

        switch ($period) {
            case 'today':
                $query->whereDate('reservation_time', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('reservation_time', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('reservation_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('reservation_time', Carbon::now()->month)
                      ->whereYear('reservation_time', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('reservation_time', Carbon::now()->subMonth()->month)
                      ->whereYear('reservation_time', Carbon::now()->subMonth()->year);
                break;
            case 'all_time':
            default:
                break;
        }

        return [
            'total_reservations' => $query->count(),
            'period' => $period,
        ];
    }

    public function getPendingReservations(): array
    {
        $reservations = reservation::with('table')
            ->where('status', 'pending')
            ->orderBy('reservation_time', 'asc')
            ->get();

        return $this->mapToDTOs($reservations);
    }
}
