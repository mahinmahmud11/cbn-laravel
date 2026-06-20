<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TariffResource\Pages;
use App\Filament\Resources\TariffResource\RelationManagers;
use App\Models\Tariff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TariffResource extends Resource
{
    protected static ?string $model = Tariff::class;

    protected static ?string $navigationLabel = 'Manajemen Tarif';
    protected static ?string $navigationGroup = 'Sistem & Keamanan';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rute Pengiriman')
                    ->schema([
                        Forms\Components\Select::make('hometown_id')
                            ->label('Asal (Origin)')
                            ->relationship('hometown', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('destination_id')
                            ->label('Tujuan (Destination)')
                            ->relationship('destination', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Harga & Diskon')
                    ->schema([
                        Forms\Components\TextInput::make('normal_price')
                            ->label('Harga Normal')
                            ->numeric()
                            ->prefix('IDR')
                            ->required(),
                        Forms\Components\TextInput::make('discount')
                            ->label('Diskon')
                            ->numeric()
                            ->prefix('IDR')
                            ->default(0),
                        Forms\Components\Toggle::make('is_promo')
                            ->label('Aktifkan Promo'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hometown.name')
                    ->label('Asal')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('destination.name')
                    ->label('Tujuan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('normal_price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_promo')
                    ->label('Promo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('discount')
                    ->label('Potongan')
                    ->money('IDR'),
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
            'index' => Pages\ListTariffs::route('/'),
            'create' => Pages\CreateTariff::route('/create'),
            'edit' => Pages\EditTariff::route('/{record}/edit'),
        ];
    }
}
