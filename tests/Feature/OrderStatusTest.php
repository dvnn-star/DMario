<?php

use App\Models\table;
use Database\Factories\OrderFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('menampilkan halaman status pesanan saat active_order_id ada dalam session', function () {
    $table = table::factory()->create();
    $order = OrderFactory::new()->create(['table_id' => $table->id]);

    $response = $this->withSession([
        'table' => [
            'id' => $table->id,
            'identifier' => $table->identifier,
        ],
        'active_order_id' => $order->id,
    ])->get(route('order.status'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('MenuQr/OrderStatus', shouldExist: false)
        ->where('order.id', $order->id)
    );
});

it('mengalihkan pengguna ke menu meja jika active_order_id tidak ada tetapi session meja ada', function () {
    $table = table::factory()->create();

    $response = $this->withSession([
        'table' => [
            'id' => $table->id,
            'identifier' => $table->identifier,
        ],
    ])->get(route('order.status'));

    $response->assertRedirect("/menu/table/{$table->identifier}");
});

it('mengembalikan status 403 jika session meja dan active_order_id tidak ada', function () {
    $response = $this->get(route('order.status'));

    $response->assertStatus(403);
});
