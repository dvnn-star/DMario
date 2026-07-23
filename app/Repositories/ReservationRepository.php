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
}
