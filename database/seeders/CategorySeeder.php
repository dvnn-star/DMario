<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'Starter'],
            ['name' => 'Soup'],
            ['name' => 'Noodle Soup'],
            ['name' => 'Snack'],
            ['name' => 'Burger'],
            ['name' => 'Share Menu'],
            ['name' => 'Pizzette'],
            ['name' => 'Pasta'],
            ['name' => 'Fried Rice and noodler'],
            ['name' => 'Asian Delight'],
            ['name' => 'Skewers'],
            ['name' => 'Authentic Sate Selectioan'],
            ['name' => 'Vegetables'],
            ['name' => 'Dessert'],
            ['name' => 'Classic Coffee'],
            ['name' => 'Manual Brew'],
            ['name' => 'Signature Coffee'],
            ['name' => 'Signature Non Coffee'],
            ['name' => 'Tea Series'],
            ['name' => 'Non Coffee'],
            ['name' => 'Mocktail'],
            ['name' => 'Fresh Juice'],
            ['name' => 'Smothies'],
            ['name' => 'Soft Drink'],
        ]);
    }
}
