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

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

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
                    ->label('Tracking Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updater.name')
                    ->label('Updated By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
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
