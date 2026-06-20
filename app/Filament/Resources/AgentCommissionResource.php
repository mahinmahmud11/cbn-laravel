<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentCommissionResource\Pages;
use App\Filament\Resources\AgentCommissionResource\RelationManagers;
use App\Models\AgentCommission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgentCommissionResource extends Resource
{
    protected static ?string $model = AgentCommission::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('agent_id')
                    ->relationship('agent', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('shipment_id')
                    ->relationship('shipment', 'tracking_number')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('IDR')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Textarea::make('notes')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('agent.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipment.tracking_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        default => 'gray',
                    }),
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
            'index' => Pages\ListAgentCommissions::route('/'),
            'create' => Pages\CreateAgentCommission::route('/create'),
            'edit' => Pages\EditAgentCommission::route('/{record}/edit'),
        ];
    }
}
