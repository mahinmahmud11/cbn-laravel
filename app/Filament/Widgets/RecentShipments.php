<?php

namespace App\Filament\Widgets;

use App\Models\ShipItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentShipments extends BaseWidget
{
    protected static ?string $heading = 'Pengiriman Terbaru';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ShipItem::query()->latest('id')->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('awb')
                    ->label('No. AWB')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender_name')
                    ->label('Pengirim')
                    ->limit(20),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Penerima')
                    ->limit(20),
                Tables\Columns\TextColumn::make('price')
                    ->label('Biaya')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === '1' ? 'success' : 'danger')
                    ->formatStateUsing(fn (string $state): string => $state === '1' ? 'Aktif' : 'Non-Aktif'),
            ]);
    }
}
