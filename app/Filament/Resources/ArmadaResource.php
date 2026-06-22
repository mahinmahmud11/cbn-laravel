<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ArmadaResource\Pages;
use App\Models\Armada;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArmadaResource extends Resource
{
    protected static ?string $model = Armada::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Armada';

    protected static ?string $modelLabel = 'Kendaraan';

    protected static ?string $pluralModelLabel = 'Armada';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    // ─── Form ────────────────────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Kendaraan')
                ->schema([
                    Forms\Components\TextInput::make('nopol')
                        ->label('Nomor Polisi')
                        ->required()
                        ->maxLength(20)
                        ->unique(ignoreRecord: true)
                        ->placeholder('B 1234 ABC')
                        ->helperText('Contoh: B 1234 ABC'),

                    Forms\Components\Select::make('jenis')
                        ->label('Jenis Kendaraan')
                        ->required()
                        ->options([
                            'motor'  => 'Motor',
                            'pickup' => 'Pickup',
                            'van'    => 'Van',
                            'truk'   => 'Truk',
                            'lainnya' => 'Lainnya',
                        ]),

                    Forms\Components\TextInput::make('merek')
                        ->label('Merek')
                        ->maxLength(50)
                        ->placeholder('Honda, Toyota, Mitsubishi, dll'),

                    Forms\Components\TextInput::make('warna')
                        ->label('Warna')
                        ->maxLength(30),

                    Forms\Components\TextInput::make('tahun')
                        ->label('Tahun Pembuatan')
                        ->numeric()
                        ->minValue(1990)
                        ->maxValue((int) date('Y') + 1),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->required()
                        ->options([
                            'aktif'       => 'Aktif',
                            'maintenance' => 'Maintenance',
                            'nonaktif'    => 'Nonaktif',
                        ])
                        ->default('aktif'),
                ])->columns(2),

            Forms\Components\Section::make('Keterangan Tambahan')
                ->schema([
                    Forms\Components\Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    // ─── Table ───────────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nopol')
                    ->label('Nopol')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'motor'   => 'info',
                        'pickup'  => 'warning',
                        'van'     => 'success',
                        'truk'    => 'danger',
                        default   => 'gray',
                    }),

                Tables\Columns\TextColumn::make('merek')
                    ->label('Merek')
                    ->searchable(),

                Tables\Columns\TextColumn::make('warna')
                    ->label('Warna'),

                Tables\Columns\TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif'       => 'success',
                        'maintenance' => 'warning',
                        'nonaktif'    => 'danger',
                        default       => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')
                    ->label('Jenis')
                    ->options([
                        'motor'   => 'Motor',
                        'pickup'  => 'Pickup',
                        'van'     => 'Van',
                        'truk'    => 'Truk',
                        'lainnya' => 'Lainnya',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif'       => 'Aktif',
                        'maintenance' => 'Maintenance',
                        'nonaktif'    => 'Nonaktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->visible(fn (): bool => auth()->user()->hasRole('super_admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->visible(fn (): bool => auth()->user()->hasRole('super_admin')),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListArmada::route('/'),
            'create' => Pages\CreateArmada::route('/create'),
            'edit'   => Pages\EditArmada::route('/{record}/edit'),
        ];
    }
}
