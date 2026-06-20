<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShipmentResource\Pages;
use App\Filament\Resources\ShipmentResource\RelationManagers;
use App\Models\Shipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tracking_number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('recipient_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('recipient_phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('origin_city')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('destination_city')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('current_status')
                    ->options([
                        'pending' => 'Pending',
                        'picked_up' => 'Picked Up',
                        'in_transit' => 'In Transit',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('destination_city')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'picked_up' => 'info',
                        'in_transit' => 'warning',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
        ];
    }
}
