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
        $legacyShipments = \Illuminate\Support\Facades\DB::table('ship_items')
            ->select(
                'id',
                'awb as tracking_number',
                'sender_name as customer_name',
                'recipient_name',
                'price',
                \Illuminate\Support\Facades\DB::raw("IF(status='1','Aktif','Non-Aktif') as current_status"),
                \Illuminate\Support\Facades\DB::raw('FROM_UNIXTIME(created_at) as created_at'),
                \Illuminate\Support\Facades\DB::raw("'legacy' as source")
            );

        $newShipments = \Illuminate\Support\Facades\DB::table('shipments')
            ->select(
                'id', 'tracking_number', 'customer_name', 'recipient_name',
                'price', 'current_status', 'created_at',
                \Illuminate\Support\Facades\DB::raw("'new' as source")
            );

        $unionQuery = $newShipments->unionAll($legacyShipments)->orderByDesc('created_at');

        return $table
            ->query(
                \App\Models\Shipment::query()->fromSub($unionQuery, 'shipments')
            )
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('No. AWB')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pengirim')
                    ->limit(20),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Penerima')
                    ->limit(20),
                Tables\Columns\TextColumn::make('price')
                    ->label('Biaya')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('current_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif', 'delivered' => 'success',
                        'Non-Aktif', 'cancelled' => 'danger',
                        default => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ]);
    }
}
