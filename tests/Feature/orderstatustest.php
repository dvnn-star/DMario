<?php

namespace Tests\Feature;

use App\Models\table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class orderstatustest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_dapat_membuat_reservasi_dengan_data_valid(): void
    {
        // 1. Arrange: Siapkan data dummy meja
        $table = table::create([
            'table_number' => '01',
            'status' => 'available',
            'qr_code_path' => 'qr/01.png',
        ]);

        $payload = [
            'name' => 'John Doe',
            'guests' => 3,
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => '14:00',
            'table_id' => $table->id,
        ];

        // 2. Act: Kirim request POST ke route reservation.store
        $response = $this->post(route('reservation.store'), $payload);

        // 3. Assert: Cek HTTP Response & Database
        $response->assertStatus(302); // Redirect back (Inertia default)
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reservations', [
            'name' => 'John Doe',
            'guests' => 3,
            'time' => '14:00',
            'table_id' => $table->id,
        ]);
    }

    /** @test */
    public function reservasi_gagal_jika_field_wajib_kosong(): void
    {
        // Act: Kirim payload kosong
        $response = $this->post(route('reservation.store'), []);

        // Assert: Pastikan session memiliki error validasi pada field wajib
        $response->assertSessionHasErrors([
            'name',
            'guests',
            'date',
            'time',
            'table_id',
        ]);

        // Dipastikan tidak ada data yang masuk ke DB
        $this->assertDatabaseCount('reservations', 0);
    }

    /** @test */
    public function reservasi_gagal_jika_id_meja_tidak_ditemukan(): void
    {
        $payload = [
            'name' => 'Jane Doe',
            'guests' => 2,
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => '18:00',
            'table_id' => 9999, // table ID tidak ada
        ];

        $response = $this->post(route('reservation.store'), $payload);

        $response->assertSessionHasErrors(['table_id']);
        $this->assertDatabaseCount('reservations', 0);
    }

    /** @test */
    public function reservasi_gagal_jika_format_waktu_salah(): void
    {
        $table = table::create([
            'table_number' => '02',
            'status' => 'available',
            'qr_code_path' => 'qr/02.png',
        ]);

        $payload = [
            'name' => 'Ahmad',
            'guests' => 1,
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => '25:99', // Format waktu tidak valid
            'table_id' => $table->id,
        ];

        $response = $this->post(route('reservation.store'), $payload);

        $response->assertSessionHasErrors(['time']);
    }
}