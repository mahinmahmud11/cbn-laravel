<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackingHistoryResource\Pages;
use App\Filament\Resources\TrackingHistoryResource\RelationManagers;
use App\Models\TrackingHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrackingHistoryResource extends Resource
{
    protected static ?string $model = TrackingHistory::class;

    protected static ?string $navigationLabel  = 'Lacak Pengiriman';
    protected static ?string $modelLabel       = 'Riwayat Status';
    protected static ?string $pluralModelLabel = 'Riwayat Status';
    protected static ?string $navigationIcon   = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup  = 'Transaksi';
    protected static ?int    $navigationSort   = 4;

    public static function getModelLabel(): string       { return 'Riwayat Status'; }
    public static function getPluralModelLabel(): string  { return 'Riwayat Status'; }
    public static function getNavigationLabel(): string   { return 'Lacak Pengiriman'; }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('shipment_id')
                    ->relationship('shipment', 'tracking_number')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->columnSpanFull(),
                // updated_by is hidden as per requirement
                Forms\Components\Hidden::make('updated_by')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shipment.tracking_number')
                    ->label('No. AWB')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updater.name')
                    ->label('Diperbarui Oleh')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
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
            'index' => Pages\ListTrackingHistories::route('/'),
            'create' => Pages\CreateTrackingHistory::route('/create'),
            'edit' => Pages\EditTrackingHistory::route('/{record}/edit'),
        ];
    }
}
