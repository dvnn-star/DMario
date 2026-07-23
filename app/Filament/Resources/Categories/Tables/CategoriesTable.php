<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;     // <-- Tambahan untuk Soft Delete
use Filament\Actions\ForceDeleteBulkAction; // <-- Tambahan untuk Soft Delete
use Filament\Actions\RestoreAction;         // <-- Tambahan untuk Soft Delete
use Filament\Actions\RestoreBulkAction;     // <-- Tambahan untuk Soft Delete
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;   // <-- Filter sampah
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(), // Tambahkan filter "With Trashed" / "Only Trashed"
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),     // Memunculkan tombol Restore saat data terhapus
                ForceDeleteAction::make(), // Memunculkan tombol Hapus Permanen
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),     // Restore banyak data sekaligus
                    ForceDeleteBulkAction::make(), // Hapus permanen banyak data sekaligus
                ]),
            ]);
    }
}
