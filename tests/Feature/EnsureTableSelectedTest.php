<?php

use App\Models\table;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('mencegah akses ke checkout jika session meja tidak ada', function () {
    $response = $this->post(route('checkout.store'), [
        'items' => [['id' => 1, 'quantity' => 1]],
        'payment_method' => 'cash',
    ]);

    $response->assertStatus(403);
});

it('memilih akses ke checkout jika session meja valid', function () {
    $table = table::factory()->create();

    $response = $this->withSession([
        'table' => [
            'id' => $table->id,
            'table_number' => $table->table_number,
            'identifier' => $table->identifier,
        ],
    ])->post(route('checkout.store'), []);

    // Response returns validation errors or 302 redirect back (not 403 forbidden)
    $this->assertNotEquals(403, $response->getStatusCode());
});
