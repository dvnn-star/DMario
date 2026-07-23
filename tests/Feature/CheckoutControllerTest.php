<?php

use App\Models\MenuItem;
use App\Models\table;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('recalculates price server-side and ignores tampered client payload', function () {
    $table = table::factory()->create();
    $menu1 = MenuItem::factory()->create(['price' => 25000]);
    $menu2 = MenuItem::factory()->create(['price' => 15000]);

    $payload = [
        'payment_method' => 'cash',
        'items' => [
            // User tampers with price on the client side, sending dummy prices
            ['id' => $menu1->id, 'quantity' => 2, 'price' => 100], 
            ['id' => $menu2->id, 'quantity' => 1, 'price' => 100], 
        ],
    ];

    $response = $this->withSession([
        'table' => [
            'id' => $table->id,
            'identifier' => $table->identifier,
            'table_number' => $table->table_number,
        ],
    ])->post(route('checkout.store'), $payload);

    $response->assertRedirect(route('order.status'));

    // Server recalculation: (2 * 25000) + (1 * 15000) = 65000
    $this->assertDatabaseHas('orders', [
        'table_id' => $table->id,
        'total_price' => 65000,
    ]);
});

it('inserts order and order details atomically', function () {
    $table = table::factory()->create();
    $menu = MenuItem::factory()->create(['price' => 10000]);

    $payload = [
        'payment_method' => 'qris',
        'items' => [
            ['id' => $menu->id, 'quantity' => 3, 'note' => 'Spicy'],
        ],
    ];

    $response = $this->withSession([
        'table' => [
            'id' => $table->id,
            'identifier' => $table->identifier,
        ],
    ])->post(route('checkout.store'), $payload);

    $response->assertRedirect(route('order.status'));

    $order = \App\Models\order::first();

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'table_id' => $table->id,
        'payment_method' => 'qris',
    ]);

    $this->assertDatabaseHas('order_details', [
        'order_id' => $order->id,
        'menu_item_id' => $menu->id,
        'quantity' => 3,
        'price' => 10000, // stored price is unit price * quantity or just unit price, based on the implementation
    ]);
});

it('rejects checkout if table session is missing', function () {
    $menu = MenuItem::factory()->create();

    $payload = [
        'payment_method' => 'cash',
        'items' => [
            ['id' => $menu->id, 'quantity' => 1],
        ],
    ];

    $response = $this->post(route('checkout.store'), $payload);

    // Expecting 403 from EnsureTableSelected middleware
    $response->assertStatus(403);
    $this->assertDatabaseCount('orders', 0);
});

it('stores active_order_id in session upon successful order placement', function () {
    $table = table::factory()->create();
    $menu = MenuItem::factory()->create();

    $payload = [
        'payment_method' => 'transfer',
        'items' => [
            ['id' => $menu->id, 'quantity' => 1],
        ],
    ];

    $response = $this->withSession([
        'table' => [
            'id' => $table->id,
            'identifier' => $table->identifier,
        ],
    ])->post(route('checkout.store'), $payload);

    $order = \App\Models\order::first();
    
    $response->assertSessionHas('active_order_id', $order->id);
});
