<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ShipItemResource\Pages;
use App\Filament\Resources\ShipItemResource\RelationManagers;
use App\Models\ShipItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipItemResource extends Resource
{
    protected static ?string $model = ShipItem::class;
    protected static ?string $navigationLabel = 'Manajemen Resi';
    protected static ?string $navigationGroup = 'Operasional Logistik';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['shipStatuses', 'user']);
        
        // Super Admin bisa melihat semua data
        if (auth()->user()->hasRole('super_admin')) {
            return $query;
        }
        
        // Agen hanya bisa melihat resi dari store_area (ID Agen) miliknya
        return $query->whereHas('user', function ($q) {
            $q->where('store_area', auth()->user()->store_area);
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Resi')
                    ->schema([
                        Forms\Components\TextInput::make('awb')
                            ->label('No. AWB')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                '0' => 'Non-Aktif',
                                '1' => 'Aktif',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Pengiriman')
                    ->schema([
                        Forms\Components\TextInput::make('sender_name')
                            ->label('Nama Pengirim')
                            ->required()
                            ->maxLength(35),
                        Forms\Components\TextInput::make('recipient_name')
                            ->label('Nama Penerima')
                            ->maxLength(35),
                        Forms\Components\TextInput::make('package_type')
                            ->label('Tipe Paket')
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->prefix('IDR')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Dimensi & Berat')
                    ->schema([
                        Forms\Components\TextInput::make('pieces')
                            ->label('Koli/Pieces')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('kilogram')
                            ->label('Berat (Kg)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('dimension')
                            ->label('Dimensi')
                            ->placeholder('P x L x T')
                            ->maxLength(11),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('awb')
                    ->label('No. AWB')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('sender_name')
                    ->label('Pengirim')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Penerima')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kilogram')
                    ->label('Berat')
                    ->suffix(' Kg')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => 'Non-Aktif',
                        '1' => 'Aktif',
                        default => 'Unknown',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->formatStateUsing(fn ($state) => $state ? \Illuminate\Support\Carbon::createFromTimestamp($state)->format('d/m/Y H:i') : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        '0' => 'Non-Aktif',
                        '1' => 'Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->hasRole('super_admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole('super_admin')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ShipStatusesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageShipItems::route('/'),
        ];
    }
}
