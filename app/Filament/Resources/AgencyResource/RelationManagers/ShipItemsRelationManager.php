<?php

namespace App\Filament\Resources\AgencyResource\RelationManagers;

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
                    ->label('No. Resi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sender_name')
                    ->label('Pengirim')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Penerima')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => '1',
                        'danger' => '0',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === '1' ? 'AKTIF' : 'NON-AKTIF'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
