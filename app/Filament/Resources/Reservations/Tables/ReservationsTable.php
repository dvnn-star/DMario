<?php

namespace App\Filament\Resources\Reservations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter; // 1. Import TrashedFilter
use Filament\Tables\Table;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Pelanggan'),

                TextColumn::make('table.table_number')
                    ->label('Meja')
                    ->default('Tanpa Meja')
                    ->sortable(),

                TextColumn::make('reservation_time')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Waktu Reservasi'),

                TextColumn::make('number_of_guests')
                    ->numeric()
                    ->sortable()
                    ->label('Jumlah Tamu'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'cancelled',
                    ]),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // 2. Filter untuk memilih data aktif / data terhapus / semua data
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),     // Tombol kembalikan data terhapus
                ForceDeleteAction::make(), // Tombol hapus permanen
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}   