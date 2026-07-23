<?php
// tests/Feature/OrderTest.php
use App\Models\MenuItem;
use App\Models\User;

it('berhasil membuat pesanan dan menghitung total harga dengan akurat', function () {
    $menu1 = MenuItem::factory()->create(['price' => 25000]); // Nasi Goreng
    $menu2 = MenuItem::factory()->create(['price' => 5000]);  // Es Teh

    $payload = [
        'items' => [
            ['menu_item_id' => $menu1->id, 'quantity' => 2], // 50.000
            ['menu_item_id' => $menu2->id, 'quantity' => 1], // 5.000
        ],
    ];

    $response = $this->postJson('/api/orders', $payload);

    $response->assertCreated();

    // Pastikan tersimpan di DB dengan total harga 55.000
    $this->assertDatabaseHas('orders', [
        'total_price' => 55000,
        'status' => 'pending',
    ]);

    // Pastikan detail pesanan terpecah dengan benar
    $this->assertDatabaseCount('order_details', 2);
});