<?php
namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            // Kolom wajib (NOT NULL)
            'customer_name'    => $this->faker->name(),
            'table_id'         => Table::factory(), // Otomatis buat Table baru jika tidak di-override
            'reservation_time' => $this->faker->dateTimeBetween('+1 hour', '+1 week')->format('Y-m-d H:i:s'),
            'number_of_guests' => $this->faker->numberBetween(1, 8),
            'status'           => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
        ];
    }
}