<?php

namespace App\AI\Context;

use App\AI\Context\Contracts\ContextBuilder;
use App\Repositories\Contracts\ReservationRepositoryInterface;
use Illuminate\Support\Facades\Cache;

/**
 * Builds the reservation context for the AI.
 */
class ReservationContext implements ContextBuilder
{
    public function __construct(
        protected ReservationRepositoryInterface $reservationRepo
    ) {
    }

    public function getName(): string
    {
        return 'reservations';
    }

    public function build(): array
    {
        $cacheDuration = config('ai.context_cache.reservation', 30);

        return Cache::remember('ai_context_reservation', $cacheDuration, function () {
            $today = $this->reservationRepo->getTodayReservations();
            $upcoming = $this->reservationRepo->getUpcomingReservations(5);

            $todaySummarized = [];
            foreach ($today as $res) {
                $time = \Carbon\Carbon::parse($res->reservationTime)->format('H:i');
                $todaySummarized[] = "{$time} - {$res->customerName} ({$res->numberOfGuests} pax)";
            }

            return [
                'today_count' => count($today),
                'today_details' => $todaySummarized,
                'upcoming_count' => count($upcoming),
                'available_slots' => 10, // Placeholder
                'peak_hours' => '19:00 - 21:00', // Placeholder
            ];
        });
    }
}
