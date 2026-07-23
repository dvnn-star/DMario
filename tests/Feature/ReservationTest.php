<?php

namespace Tests\Feature;

use App\Models\reservation;
use App\Models\table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_reservation_with_valid_data(): void
    {
        $table = table::factory()->create();

        $payload = [
            'name' => 'John Doe',
            'guests' => 3,
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => '14:00',
            'table_id' => $table->id,
        ];

        $response = $this->post(route('reservation.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reservations', [
            'customer_name' => 'John Doe',
            'number_of_guests' => 3,
            'table_id' => $table->id,
        ]);
    }

    public function test_reservation_fails_if_required_fields_are_missing(): void
    {
        $response = $this->post(route('reservation.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'guests',
            'date',
            'time',
            'table_id',
        ]);

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_reservation_fails_if_table_id_does_not_exist(): void
    {
        $payload = [
            'name' => 'Jane Doe',
            'guests' => 2,
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => '18:00',
            'table_id' => 9999,
        ];

        $response = $this->post(route('reservation.store'), $payload);

        $response->assertSessionHasErrors(['table_id']);
        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_reservation_prevents_double_booking_same_table_and_time(): void
    {
        $table = table::factory()->create();
        $date = now()->addDays(2)->format('Y-m-d');
        $time = '19:00';
        $datetime = $date.' '.$time;

        reservation::factory()->create([
            'table_id' => $table->id,
            'reservation_time' => $datetime,
            'status' => 'confirmed',
        ]);

        $payload = [
            'name' => 'Budi',
            'guests' => 2,
            'date' => $date,
            'time' => $time,
            'table_id' => $table->id,
        ];

        $response = $this->post(route('reservation.store'), $payload);

        $response->assertSessionHasErrors(['table_id']);
        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_reservation_rejects_past_dates(): void
    {
        $table = table::factory()->create();

        $payload = [
            'name' => 'Past Date User',
            'guests' => 2,
            'date' => now()->subDay()->format('Y-m-d'), // Yesterday
            'time' => '14:00',
            'table_id' => $table->id,
        ];

        $response = $this->post(route('reservation.store'), $payload);

        $response->assertSessionHasErrors(['date']);
        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_reservation_rejects_dates_more_than_7_days_in_advance(): void
    {
        $table = table::factory()->create();

        $payload = [
            'name' => 'Future Date User',
            'guests' => 2,
            'date' => now()->addDays(8)->format('Y-m-d'), // 8 days later
            'time' => '14:00',
            'table_id' => $table->id,
        ];

        $response = $this->post(route('reservation.store'), $payload);

        $response->assertSessionHasErrors(['date']);
        $this->assertDatabaseCount('reservations', 0);
    }
}
