<?php

declare(strict_types=1);

namespace App\Filament\Resources\SuratJalanResource\RelationManagers;

use App\Models\Shipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ShipmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'shipments';

    protected static ?string $title = 'Resi dalam Surat Jalan';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('tracking_number')
                ->label('No. AWB')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tracking_number')
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('No. AWB')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pengirim')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Penerima'),
                Tables\Columns\TextColumn::make('destination_city')
                    ->label('Kota Tujuan'),
                Tables\Columns\TextColumn::make('current_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'    => 'gray',
                        'picked_up'  => 'info',
                        'in_transit' => 'warning',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    }),
                Tables\Columns\TextColumn::make('pieces')
                    ->label('Koli'),
                Tables\Columns\TextColumn::make('weight_kg')
                    ->label('Berat (kg)'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Tambah Resi')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['tracking_number', 'customer_name', 'recipient_name']),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Lepas dari Surat Jalan'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                    ->label('Lepas Terpilih'),
            ]);
    }
}
