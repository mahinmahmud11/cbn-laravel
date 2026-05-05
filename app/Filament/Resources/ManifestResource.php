<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManifestResource\Pages;
use App\Filament\Resources\ManifestResource\RelationManagers;
use App\Models\Manifest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManifestResource extends Resource
{
    protected static ?string $model = Manifest::class;
    protected static ?string $navigationLabel = 'Manajemen Manifest';
    protected static ?string $navigationGroup = 'Operasional Logistik';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\TextInput::make('manifest_number')
                            ->label('Nomor Manifest')
                            ->default('MNF-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2))))
                            ->readOnly()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'transit' => 'Dalam Perjalanan (Transit)',
                                'arrived' => 'Sampai (Arrived)',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->required()
                            ->default('draft')
                            ->native(false),
                    ])->columns(2),

                Forms\Components\Section::make('Rute & Armada')
                    ->schema([
                        Forms\Components\Select::make('origin_agency_id')
                            ->label('Asal (Origin)')
                            ->relationship('originAgency', 'agency_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('destination_agency_id')
                            ->label('Tujuan (Destination)')
                            ->relationship('destinationAgency', 'agency_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('driver_name')
                            ->label('Nama Driver')
                            ->placeholder('Masukkan nama driver'),
                        Forms\Components\TextInput::make('vehicle_plate')
                            ->label('Plat Nomor Kendaraan')
                            ->placeholder('Contoh: B 1234 ABC'),
                    ])->columns(2),

                Forms\Components\Section::make('Catatan Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('manifest_number')
                    ->label('No. Manifest')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('originAgency.agency_name')
                    ->label('Asal')
                    ->sortable(),
                Tables\Columns\TextColumn::make('destinationAgency.agency_name')
                    ->label('Tujuan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'transit' => 'warning',
                        'arrived' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('driver_name')
                    ->label('Driver')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ship_items_count')
                    ->label('Jumlah Resi')
                    ->counts('shipItems'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'transit' => 'Transit',
                        'arrived' => 'Arrived',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('print')
                    ->label('Cetak Manifest')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Manifest $record): string => route('print.manifest', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\ShipItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListManifests::route('/'),
            'create' => Pages\CreateManifest::route('/create'),
            'view' => Pages\ViewManifest::route('/{record}'),
            'edit' => Pages\EditManifest::route('/{record}/edit'),
        ];
    }
}
