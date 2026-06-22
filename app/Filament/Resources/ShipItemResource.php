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
use App\Services\PricingService;

class ShipItemResource extends Resource
{
    protected static ?string $model = ShipItem::class;
    
    protected static ?string $modelLabel = 'Arsip Resi';
    protected static ?string $pluralModelLabel = 'Daftar Arsip Resi';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 10;
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

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
                        Forms\Components\Select::make('origin_district_id')
                            ->label('Asal (Kecamatan)')
                            ->relationship('originDistrict', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($set, $get) => self::updatePrice($set, $get)),
                        Forms\Components\Select::make('destination_district_id')
                            ->label('Tujuan (Kecamatan)')
                            ->relationship('destinationDistrict', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($set, $get) => self::updatePrice($set, $get)),
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
                            ->required()
                            ->readOnly()
                            ->helperText('Dihitung otomatis berdasarkan rute dan berat.'),
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
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $get) => self::updatePrice($set, $get)),
                        Forms\Components\TextInput::make('dimension')
                            ->label('Dimensi')
                            ->placeholder('P x L x T')
                            ->maxLength(11)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $get) => self::updatePrice($set, $get)),
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
                Tables\Actions\Action::make('print')
                    ->label('Cetak Label')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (ShipItem $record): string => route('print.resi', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('print_bulk')
                        ->label('Cetak Label Massal')
                        ->icon('heroicon-o-printer')
                        ->color('info')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $ids = $records->pluck('id')->implode(',');
                            return redirect()->route('print.resi.bulk', ['ids' => $ids]);
                        }),
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

    /**
     * Logika untuk memperbarui harga secara otomatis.
     */
    public static function updatePrice(callable $set, callable $get): void
    {
        $originId = $get('origin_district_id');
        $destId = $get('destination_district_id');
        $weight = (float) $get('kilogram');
        $dimension = $get('dimension');

        if (!$originId || !$destId || $weight <= 0) {
            return;
        }

        // Parsing Dimensi (P x L x T)
        $p = $l = $t = 0.0;
        if ($dimension && preg_match('/(\d+)\s*[xX]\s*(\d+)\s*[xX]\s*(\d+)/', $dimension, $matches)) {
            $p = (float) $matches[1];
            $l = (float) $matches[2];
            $t = (float) $matches[3];
        }

        // Hitung via Service
        $rates = app(PricingService::class)->calculate(
            (string) $originId,
            (string) $destId,
            $weight,
            $p > 0 ? $p : null,
            $l > 0 ? $l : null,
            $t > 0 ? $t : null
        );

        if ($rates->isNotEmpty()) {
            // Ambil harga dari layanan pertama (default reguler)
            $set('price', $rates->first()['total_fee']);
        }
    }
}
