<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('dapat menampilkan halaman login filament', function () {
    get('/admin/login')
        ->assertSuccessful();
});

it('mencegah guest mengakses dashboard filament', function () {
    get('/admin')
        ->assertRedirect('/admin/login');
});

it('memungkinkan admin mengakses dashboard filament', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    actingAs($user)
        ->get('/admin')
        ->assertSuccessful();
});

it('memungkinkan kasir mengakses dashboard filament', function () {
    $user = User::factory()->create([
        'role' => 'cashier',
    ]);

    actingAs($user)
        ->get('/admin')
        ->assertSuccessful();
});

it('mencegah user tanpa role yang sesuai mengakses dashboard filament', function () {
    $user = User::factory()->create([
        'role' => 'customer',
    ]);

    actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});
