<?php

namespace App\Filament\Resources\Reservations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Pelanggan'),

                Select::make('table_id')
                    ->relationship('table', 'table_number') // Sesuaikan 'table_number' dengan kolom nomor di tabel 'tables'
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Pilih Meja'),

                DateTimePicker::make('reservation_time')
                    ->required()
                    ->seconds(false)
                    ->displayFormat('d/m/Y H:i')
                    ->label('Waktu Reservasi'),

                TextInput::make('number_of_guests')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->label('Jumlah Tamu'),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required()
                    ->label('Status Reservasi'),
            ]);
    }
}
