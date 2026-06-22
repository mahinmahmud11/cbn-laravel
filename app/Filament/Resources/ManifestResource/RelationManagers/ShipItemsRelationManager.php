<?php

namespace App\Filament\Resources\ManifestResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'shipItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('awb')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('awb')
            ->columns([
                Tables\Columns\TextColumn::make('awb')
                    ->label('No. AWB')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sender_name')
                    ->label('Pengirim'),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Penerima'),
                Tables\Columns\TextColumn::make('kilogram')
                    ->label('Berat (Kg)'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Disable attach action for legacy ship items
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
