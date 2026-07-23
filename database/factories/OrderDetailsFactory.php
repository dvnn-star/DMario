<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\orderDetails>
 */
class OrderDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => order::factory(),
            'menu_item_id' => MenuItem::factory(),
            'quantity' => $this->faker->numberBetween(1, 4),
            'price' => $this->faker->numberBetween(10000, 50000),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
