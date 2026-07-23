<?php

namespace Database\Factories;

use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

class TableFactory extends Factory
{
    protected $model = Table::class;

    public function definition(): array
    {
        return [
            // Harus integer
            'table_number' => $this->faker->unique()->numberBetween(1, 100),

            // UUID char(36) unik
            'identifier' => $this->faker->unique()->uuid(),

            // Path QR wajib diisi & unik
            'qr_code_path' => 'qrcodes/table-'.$this->faker->unique()->numberBetween(1, 100).'.png',

            // Enum
            'status' => 'available',
        ];
    }
}
