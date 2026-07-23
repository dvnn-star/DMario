<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('table_id')
                    ->relationship('table', 'table_number') // Ganti 'name' dengan nama kolom di tabel 'tables' (misal: 'number')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Pilih Meja'),

                TextInput::make('total_price')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->label('Total Harga'),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required()
                    ->label('Status Pesanan'),

                TextInput::make('payment_method')
                    ->placeholder('Contoh: QRIS, Cash, Transfer')
                    ->label('Metode Pembayaran'),
            ]);
    }
}
