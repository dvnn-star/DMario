<?php

namespace Database\Factories;

use App\Models\table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\order>
 */
class OrderFactory extends Factory
{
    protected $model = \App\Models\order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'table_id' => table::factory(),
            'total_price' => $this->faker->numberBetween(20000, 200000),
            'status' => 'pending',
            'payment_method' => $this->faker->randomElement(['cash', 'qris', 'transfer']),
        ];
    }
}
