<?php

namespace Database\Seeders;

use App\Models\table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Siapkan direktori storage 'qrcodes'
        if (!Storage::disk('public')->exists('qrcodes')) {
            Storage::disk('public')->makeDirectory('qrcodes');
        }

        $this->command->info('Sedang men-generate 20 Meja dan QR Code...');

        for ($i = 1; $i <= 20; $i++) {
            $qrPath = "qrcodes/table-{$i}.png";

            // 2. Simpan/Update data ke DB.
            // Event booted() di Model table akan otomatis mengisi 'identifier' (UUID).
            $table = table::updateOrCreate(
                ['table_number' => $i],
                [
                    'qr_code_path' => $qrPath,
                    'status'       => 'available',
                ]
            );

            // 3. Ambil identifier dari Model
            $urlToEncode = config('app.url') . "/menu/table/" . $table->identifier;
            $apiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($urlToEncode);

            try {
                // 4. Unduh QR Code dari API dan simpan ke Storage
                $response = Http::timeout(10)->get($apiUrl);

                if ($response->successful()) {
                    Storage::disk('public')->put($qrPath, $response->body());
                } else {
                    $this->command->error("Gagal mengunduh QR Meja {$i}: API Error.");
                }
            } catch (\Exception $e) {
                $this->command->error("Gagal koneksi saat unduh QR Meja {$i}: " . $e->getMessage());
            }
        }

        $this->command->info('Selesai! Pastikan sudah menjalankan: php artisan storage:link');
    }
}