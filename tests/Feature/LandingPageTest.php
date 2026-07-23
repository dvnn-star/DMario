<?php

use App\Models\MenuItem;
use App\Models\table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('menampilkan landing page welcome dengan best sellers', function () {
    MenuItem::factory()->create([
        'is_available' => true,
        'is_recommended' => true,
        'name' => 'Pizza Supreme',
        'price' => 150000,
    ]);

    $response = $this->get(route('landingpage'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('landingpage/Welcome')
        ->has('bestSellers', 1)
        ->where('bestSellers.0.title', 'PIZZA SUPREME')
        ->where('bestSellers.0.price', '150K')
    );
});

it('menampilkan menu dan menyimpan menuItems ke cache', function () {
    MenuItem::factory()->count(3)->create();

    $this->assertFalse(Cache::has('menu_items_with_categories'));

    $response = $this->get(route('menu'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('landingpage/menu')
        ->has('menuItems', 3)
    );

    $this->assertTrue(Cache::has('menu_items_with_categories'));
});

it('dapat memproses scan qr meja dan menyimpan data meja ke session', function () {
    $table = table::factory()->create();

    $response = $this->get(route('menu.table', ['identifier' => $table->identifier]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('MenuQr/ShowMenu', shouldExist: false)
        ->where('table.id', $table->id)
        ->where('table.table_number', $table->table_number)
    );

    $this->assertEquals(session('table.id'), $table->id);
    $this->assertEquals(session('table.identifier'), $table->identifier);
});
