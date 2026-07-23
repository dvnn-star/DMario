<?php

use App\Models\reservation;
use App\Models\table;
use App\Models\User;
use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

it('dapat menampilkan halaman login filament', function () {
    get('/admin/login')
        ->assertSuccessful();
});

it('mencegah guest mengakses dashboard filament', function () {
    get('/admin')
        ->assertRedirect('/admin/login');
});

it('memungkinkan user terautentikasi mengakses dashboard filament', function () {
    $user = User::factory()->create([
        'role' => 'admin', 
    ]);

    actingAs($user)
        ->get('/admin')
        ->assertSuccessful();
});

it('mencegah reservasi pada meja dan jam yang sudah terisi', function () {
    $table = table::factory()->create();

    // Reservasi eksisting: Meja 1 jam 19:00 - 20:00
    reservation::factory()->create([
        'table_id' => $table->id,
        'reservation_time' => '2026-07-25 19:00:00',
        'status' => 'confirmed',
    ]);

    // Request baru di jam yang sama
// Menggunakan nama route langsung
$response = $this->postJson(route('reservation.store'), [
    'table_id' => $table->id,
    'reservation_time' => '2026-07-22 19:00:00',
    'customer_name' => 'Budi',
    'number_of_guests' => 2,
]);

$response->assertStatus(422)
    ->assertJsonValidationErrors(['reservation_time']);
});