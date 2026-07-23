<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            ['name' => 'Seafood'],
            ['name' => 'Vegetarian'],
            ['name' => 'Desserts'],
            ['name' => 'Beverages'],
            ['name' => 'Appetizers'],
            ['name' => 'Main Course'],
            ['name' => 'Salads'],
            ['name' => 'Soups'],
            ['name' => 'Pasta'],
            ['name' => 'Grilled Dishes'],
        ];
    }
}
