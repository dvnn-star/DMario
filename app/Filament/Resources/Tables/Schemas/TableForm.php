<?php

namespace App\Filament\Resources\Tables\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('table_number')
                ->label('Nomor Meja')
                ->numeric()
                ->required()
                ->unique(ignoreRecord: true),

            Select::make('status')
                ->options([
                    'available' => 'Tersedia',
                    'occupied' => 'Terisi',
                    'reserved' => 'Direservasi',
                ])
                ->default('available')
                ->required(),

            TextInput::make('identifier')
                ->label('UUID Identifier')
                ->default(fn () => (string) Str::uuid())
                ->required()
                ->readOnly()
                ->dehydrated()
                ->unique(ignoreRecord: true),

            FileUpload::make('qr_code_path')
                ->label('File QR Code')
                ->image()
                ->directory('qrcodes')
                ->required(fn (string $operation) => $operation === 'create')
                ->unique(ignoreRecord: true),
        ]);
    }
}